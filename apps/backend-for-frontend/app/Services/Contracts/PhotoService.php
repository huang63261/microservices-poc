<?php

namespace App\Services\Contracts;

interface PhotoService
{
    public function getPhotosOfProducts(array $productIds): array;
    public function getPhotosOfProduct(string $productId): array;
}