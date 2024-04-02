<?php

namespace App\Services;

use App\Repositories\InventoryRepository;

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
                $inventory = $inventories[$productId];

                if ($inventory['available_quantity'] < $requiredQuantity) {
                    $insufficientCollect[] = "Product ID: {$productId} is insufficient. Available Quantity: {$inventory['available_quantity']}";
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
}