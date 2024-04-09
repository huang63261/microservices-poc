<?php

namespace App\Services;

use App\Services\Http\AbstractHttpRequest;
use Illuminate\Support\Facades\Http;

class ReviewService extends AbstractHttpRequest
{
    public function __construct() {
        $this->http = Http::review();
    }

    public function getReviewsOfProducts(array $productIds)
    {
        $reviews = $this->http->send(
            method:'POST',
            url:'/product-reviews/batch-loading',
            options: [
                'json' => [
                    'product_ids' => $productIds
                ]
            ]
        )->json();

        return $reviews;
    }

    public function getReviewsOfProduct(string $productId)
    {
        $reviews = $this->http->send(
            method:'GET',
            url:"/product-reviews?product_id={$productId}",
        )->json();

        return $reviews;
    }
}