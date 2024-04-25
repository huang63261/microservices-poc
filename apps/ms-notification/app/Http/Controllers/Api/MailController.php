<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderCompleted;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MailController extends Controller
{
    /**
     * Send mail notification
     */
    public function mail(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'total_price' => 'required|numeric',
            'created_at' => 'required|date',
            'status' => 'required|string',
        ]);

        $order = (object) [
            'id' => $request->input('order_id'),
            'total_price' => $request->input('total_price'),
            'created_at' => $request->input('created_at'),
            'status' => $request->input('status'),
        ];

        Mail::to("customer@example.com")->send(new OrderCompleted($order));
    }

    /**
     * Handle a Pub/Sub push request.
     */
    public function handlePubSubPush(Request $request)
    {
        $request->validate([
            'message.data' => 'required',
        ]);

        // Decode the Pub/Sub message.
        $message = json_decode(base64_decode($request->input('message.data')), true);

        if (!is_array($message)) {
            return response()->json([
                'message' => 'Invalid message'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Validate the message.
        $validator = Validator::make($message, [
            'order_id' => 'required|integer',
            'total_price' => 'required|numeric',
            'created_at' => 'required|date',
            'status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()
            ], 400);
        }

        // Create an order object from the message.
        $order = (object) [
            'id' => $message['order_id'],
            'total_price' => $message['total_price'],
            'created_at' => $message['created_at'],
            'status' => $message['status'],
        ];

        // Send the email.
        Mail::to("customer@example.com")->send(new OrderCompleted($order));

        return response()->json([
            'message' => 'Email sent successfully'
        ], Response::HTTP_OK);
    }
}
