<?php

namespace App\Services;

use App\Services\Contracts\ReviewService as ReviewServiceContract;
use App\Services\Http\HttpRequest;
use Illuminate\Support\Facades\Http;

class ReviewService implements ReviewServiceContract
{
    public function __construct(
        protected HttpRequest $client
    ) {}

    public function getReviewsOfProducts(array $productIds): array
    {
        return $this->client->send(
            method:'POST',
            uri:'/product-reviews/batch-loading',
            options: [
                'json' => [
                    'product_ids' => $productIds
                ]
            ]
        );
    }

    public function getReviewsOfProduct(string $productId): array
    {
        return $this->client->send(
            method:'GET',
            uri:"/product-reviews?product_id={$productId}",
        );
    }
}