<?php

namespace App\Services;

use App\Services\Http\HttpRequest;
use Illuminate\Support\Facades\Http;

class ReviewService
{
    protected HttpRequest $client;

    public function __construct() {
        $this->client = new HttpRequest(Http::review());
    }

    public function getReviewsOfProducts(array $productIds)
    {
        $reviews = $this->client->send(
            method:'POST',
            uri:'/product-reviews/batch-loading',
            options: [
                'json' => [
                    'product_ids' => $productIds
                ]
            ]
        );

        return $reviews;
    }

    public function getReviewsOfProduct(string $productId)
    {
        $reviews = $this->client->send(
            method:'GET',
            uri:"/product-reviews?product_id={$productId}",
        )->json();

        return $reviews;
    }
}