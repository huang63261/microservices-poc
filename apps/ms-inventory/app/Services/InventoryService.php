<?php

namespace App\Services;

use App\Repositories\InventoryRepository;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    public function __construct(
        protected InventoryRepository $inventoryRepository
    ) {}

    /**
     * Check the availability of the products in the inventory.
     *
     * @param array<array><string,int> $items
     * @return array
     */
    public function checkInventory(array $items)
    {
        try {
            $response = [
                'is_available' => 1,
                'detail' => 'All products are available'
            ];

            $insufficientCollect = [];

            $prodcutIds = array_column($items, 'product_id');

            $inventories = $this->inventoryRepository->findByProductIds($prodcutIds);
            $inventories = array_column($inventories, null, 'product_id');

            foreach ($items as $item) {
                $productId = $item['product_id'];
                $requiredQuantity = $item['quantity'];

                // Check if the product has any inventory
                if (!isset($inventories[$productId]) || empty($inventories[$productId])) {
                    $insufficientCollect[] = "Product ID: {$productId} Not in stock.";
                    continue;
                };

                // Check if the product has enough quantity
                $inventory = $inventories[$productId];

                if ($inventory['available_quantity'] < $requiredQuantity) {
                    $insufficientCollect[] = "Product ID: {$productId} is insufficient. Available Quantity: {$inventory['available_quantity']}";
                    continue;
                }
            }

            if (!empty($insufficientCollect)) {
                throw new \Exception(implode(', ', $insufficientCollect));
            }
        } catch (\Exception $e) {
            $response = [
                'is_available' => 0,
                'detail' => $e->getMessage(),
            ];
        }

        return $response;
    }

    /**
     * Lock the inventory for the products.
     *
     * @param array<array><string,int> $items
     * @return void
     */
    public function lockInventory(array $items)
    {
        try {
            $response = [
                'is_locked' => 1,
                'detail' => 'Successfully locked the inventory.'
            ];

            DB::beginTransaction();

            foreach ($items as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];

                $this->inventoryRepository->lockInventory($productId, $quantity);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'is_locked' => 0,
                'detail' => $e->getMessage(),
            ];
        }

        return $response;
    }

    public function unlockInventory(array $items)
    {
        try {
            $response = [
                'is_unlocked' => 1,
                'detail' => 'Successfully unlocked the inventory.'
            ];

            DB::beginTransaction();

            foreach ($items as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];

                $this->inventoryRepository->unlockInventory($productId, $quantity);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'is_unlocked' => 0,
                'detail' => $e->getMessage(),
            ];
        }

        return $response;
    }

    public function deductInventory(array $items)
    {
        try {
            $response = [
                'is_deducted' => 1,
                'detail' => 'Successfully deducted the inventory.'
            ];

            DB::beginTransaction();

            foreach ($items as $item) {
                $productId = $item['product_id'];
                $quantity = $item['quantity'];

                $this->inventoryRepository->deductInventory($productId, $quantity);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $response = [
                'is_deducted' => 0,
                'detail' => $e->getMessage(),
            ];
        }

        return $response;
    }
}