<?php

namespace App\Services\Contracts;

interface ReviewService
{
    public function getReviewsOfProducts(array $productIds): array;
    public function getReviewsOfProduct(string $productId): array;
}