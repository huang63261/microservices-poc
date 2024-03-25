<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function __construct(
        protected Order $order
    ) {}

    public function all(
        ?int $perPage = null,
        ?int $customer_id = null,
    ) {
        return $this->order
            ->when($customer_id, function ($query, $customer_id) {
                return $query->where('customer_id', $customer_id);
            })
            ->paginate($perPage);
    }

    public function create(array $data)
    {
        return $this->order->create($data);
    }

    public function find(int $id)
    {
        return $this->order->find($id);
    }

    public function update(int $id, array $data)
    {
        $order = $this->order->findOrFail($id);

        if (!$order->update($data)) {
            return false;
        }

        return $order;
    }

    public function delete(int $id)
    {
        $order = $this->order->findOrFail($id);
        $order->delete();

        return $order;
    }
}