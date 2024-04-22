<?php

namespace App\Services\Contracts;

interface ProductService
{
    public function getAll(array $queryParams = []): array;
    public function getOne(string $productId): array;
}