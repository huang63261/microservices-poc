<?php

namespace App\Services\Gcp;

use App\Services\Contracts\ReviewService as ReviewServiceContract;
use App\Services\Http\GoogleCloudApiRequest;

class ReviewService implements ReviewServiceContract
{
    public function __construct(
        protected GoogleCloudApiRequest $client
    ) {}

    public function getReviewsOfProducts(array $productIds): array
    {
        return $this->client->send(
            method:'POST',
            uri:'/api/product-reviews/batch-loading',
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
            uri:"/api/product-reviews?product_id={$productId}",
        );
    }
}