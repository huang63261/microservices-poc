<?php

namespace App\Services\Contracts;

interface InventoryService
{
    public function getInventoryOfProducts(array $productIds): array;
    public function getInventoryOfProduct(string $productId): array;
}