<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\checkoutServiceManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class checkoutController extends Controller
{
    public function __construct(
        protected checkoutServiceManager $checkoutServiceManager
    ) {}

    public function checkoutProcess(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|integer',
            'items.*.quantity' => 'required|integer'
        ]);

        $items = $request->input('items');

        $order = $this->checkoutServiceManager->checkout($items);

        return response()->json($order, Response::HTTP_CREATED);
    }

    public function paymentProcess(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'payment_method' => 'required|string'
        ]);

        $orderId = $request->input('order_id');
        $paymentMethod = $request->input('payment_method');

        $payment = $this->checkoutServiceManager->payment($orderId, $paymentMethod);

        return response()->json($payment, Response::HTTP_OK);
    }
}
