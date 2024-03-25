<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct(
        protected OrderRepository $orderRepository
    ) {}

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
        return new OrderResource(
            $this->orderRepository->create(
                $request->validate([
                    'customer_id' => 'required|integer',
                    'total_price' => 'required|numeric',
                ])
            )
        );
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
                'customer_id' => 'required|integer',
                'total_price' => 'required|numeric',
            ])
        );

        if (!$order) {
            return response()->json([
                'message' => 'Update failed'
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
