<?php

namespace App\Services;

use App\Repositories\OrderDetailRepository;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected OrderRepository $orderRepository,
        protected OrderDetailRepository $orderDetailRepository
    ) {}

    /**
     * Create a new order.
     *
     * @param array $data
     * @return \App\Models\Order
     */
    public function createOrder(array $data)
    {
        try {
            DB::beginTransaction();
            $order = $this->orderRepository->create([
                'customer_id' => $data['customer_id'],
                'total_price' => $data['total_price'],
                'status' => 0
            ]);

            foreach ($data['items'] as $item) {
                $this->orderDetailRepository->create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $order;
    }

    public function cancelOrder(int $orderId)
    {
        return $this->orderRepository->update($orderId, ['status' => 3]);
    }
}