<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\TransactionLog;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderService $orderService
    ) {
        $this->middleware('idempotency')->only('store', 'update');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return OrderResource::collection(
            $this->orderRepository->all(
                ...$request->validate([
                    'customer_id' => 'integer',
                    'per_page' => 'integer',
                ])
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $transactionLog = TransactionLog::create([
            'transaction_uuid' => $request->header('transaction-uuid'),
            'service_identifier' => 'order',
            'order_id' => null,
            'action' => 'order.create',
            'status' => 'pending',
            'detail' => json_encode($request->all()),
        ]);

        try {
            $order = $this->orderService->createOrder(
                $request->validate([
                    'customer_id' => 'required|integer',
                    'total_price' => 'required|numeric',
                    'items' => 'required|array',
                    'items.*.product_id' => 'required|integer',
                    'items.*.product_name' => 'required|string',
                    'items.*.quantity' => 'required|integer',
                    'items.*.price' => 'required|numeric',
                ])
            );
        } catch (\Exception $e) {
            $transactionLog->update([
                'status' => 'failed',
                'detail' => $e->getMessage(),
            ]);

            return response()->json(['message' => 'Failed to create order'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $transactionLog->update([
            'order_id' => $order->id,
            'status' => 'completed',
        ]);

        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $order = $this->orderRepository->update(
            $order->id,
            $request->validate([
                'customer_id' => 'integer',
                'total_price' => 'numeric',
                'status' => 'string',
            ])
        );

        if (!$order) {
            return response()->json([
                'message' => 'Update failed'
            ], Response::HTTP_NOT_FOUND);
        }

        return new OrderResource($order);
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
        ]);

        $order = $this->orderService->cancelOrder($request->input('order_id'));

        if (!$order) {
            return response()->json([
                'message' => 'Cancel failed'
            ], Response::HTTP_NOT_FOUND);
        }

        return new OrderResource($order);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $this->orderRepository->delete($order->id);

        return response()->noContent();
    }
}
