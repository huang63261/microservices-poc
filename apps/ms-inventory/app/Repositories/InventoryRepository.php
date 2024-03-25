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

    public function find(int|string $product_id)
    {
        return $this->inventory->find($product_id);
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

    public function update(int|string $product_id, array $data)
    {
        $inventory = $this->inventory->find($product_id);
        if (!$inventory->update($data)) {
            return false;
        }

        return $inventory;
    }

    public function delete(int|string $product_id)
    {
        return $this->inventory->destroy($product_id);
    }
}
