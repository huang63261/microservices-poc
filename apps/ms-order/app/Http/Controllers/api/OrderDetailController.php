<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderDetailResource;
use App\Models\OrderDetail;
use App\Repositories\OrderDetailRepository;
use Illuminate\Http\Request;

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
     * Display the specified resource.
     */
    public function show(OrderDetail $orderDetail)
    {
        return new OrderDetailResource($orderDetail);
    }
}
