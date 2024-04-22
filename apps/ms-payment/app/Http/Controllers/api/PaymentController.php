<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'success' => 'boolean',
        ]);

        // Mock payment process
        sleep(2);

        if ($request->has('success') && $request->input('success')) {
            return response()->json([
                'code' => Response::HTTP_OK,
                'message' => 'Payment success',
            ]);
        }

        return response()->json([
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => 'Payment failed',
        ]);
    }

    public function refund(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'amount' => 'required|numeric',
        ]);

        // Mock refund process
        sleep(2);

        return response()->json([
            'code' => Response::HTTP_OK,
            'message' => 'Refund success',
        ]);
    }
}
