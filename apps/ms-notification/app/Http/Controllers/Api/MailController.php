<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderCompleted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * Send mail notification
     */
    public function mail(Request $request)
    {
        if ($request->has('order')) {
            $order = (object) $request->order;
        } else {
            $order = (object) [
                'id' => 1,
                'order_date' => '2024-03-10',
                'total' => 1000,
                'shipping_address' => '123 Main St, New York, NY 10030',
                'payment_method' => 'Credit Card',
            ];
        }

        Mail::to("customer@example.com")->send(new OrderCompleted($order));
    }
}
