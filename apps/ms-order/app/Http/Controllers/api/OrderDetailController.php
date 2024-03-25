<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderDetailResource;
use App\Models\OrderDetail;
use App\Repositories\OrderDetailRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderDetailController extends Controller
{
    public function __construct(
        protected OrderDetailRepository $orderDetailRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return OrderDetailResource::collection(
            $this->orderDetailRepository->all(
                ...$request->validate([
                    'order_id' => 'integer',
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
        $orderDetail = $this->orderDetailRepository->create(
            $request->validate([
                'order_id' => 'required|integer',
                'product_id' => 'required|integer',
                'product_name' => 'required|string',
                'price' => 'required|integer',
                'quantity' => 'required|integer',
            ])
        );

        return new OrderDetailResource($orderDetail);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderDetail $orderDetail)
    {
        return new OrderDetailResource($orderDetail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderDetail $orderDetail)
    {
        $orderDetail = $this->orderDetailRepository->update(
            $orderDetail->id,
            $request->validate([
                'order_id' => 'integer',
                'product_id' => 'integer',
                'product_name' => 'string',
                'price' => 'integer',
                'quantity' => 'integer',
            ])
        );

        if (!$orderDetail) {
            return response()->json([
                'message' => 'Update failed'
            ], Response::HTTP_NOT_FOUND);
        }

        return new OrderDetailResource($orderDetail);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderDetail $orderDetail)
    {
        $this->orderDetailRepository->delete($orderDetail->id);

        return response()->noContent();
    }
}
