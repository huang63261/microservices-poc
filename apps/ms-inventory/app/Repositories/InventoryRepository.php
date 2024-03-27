<?php

namespace App\Repositories;

use App\Models\Inventory;

class InventoryRepository
{
    public function __construct(
        protected Inventory $inventory
    ) {}

    public function all()
    {
        return $this->inventory->all();
    }

    public function findByProductIds(array $productId)
    {
        return $this->inventory
            ->whereIn('product_id', $productId)
            ->get()
            ->toArray();
    }

    public function findByProductId(string $productId)
    {
        return $this->inventory->find($productId);
    }

    public function create(array $data)
    {
        try {
            $inventory = $this->inventory->create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $inventory;
    }

    public function update(string $productId, array $data)
    {
        $inventory = $this->inventory->find($productId);
        if (!$inventory->update($data)) {
            return false;
        }

        return $inventory;
    }

    public function delete(string $productId)
    {
        return $this->inventory->destroy($productId);
    }
}
