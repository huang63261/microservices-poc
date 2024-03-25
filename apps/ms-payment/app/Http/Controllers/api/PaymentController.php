<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        // Mock payment process
        sleep(2);

        return response()->json([
            'message' => 'Payment success',
        ]);
    }
}
