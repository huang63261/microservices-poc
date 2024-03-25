<?php

namespace App\Repositories;

use App\Models\OrderDetail;

class OrderDetailRepository
{
    public function __construct(
        protected OrderDetail $orderDetail
    ) {}

    public function all(
        ?int $perPage = null,
        ?int $order_id = null,
    ) {
        return $this->orderDetail
            ->when($order_id, function ($query, $order_id) {
                return $query->where('order_id', $order_id);
            })
            ->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->orderDetail->create($data);
    }

    public function update(int $id, array $data)
    {
        $orderDetail = $this->orderDetail->findOrFail($id);
        if (!$orderDetail->update($data)) {
            return false;
        }

        return $orderDetail;
    }

    public function delete(int $id)
    {
        $orderDetail = $this->orderDetail->findOrFail($id);
        $orderDetail->delete();

        return $orderDetail;
    }
}