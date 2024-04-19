<?php

namespace App\Services;

use App\Services\Http\HttpRequest;
use Illuminate\Support\Facades\Http;

class PhotoService
{
    protected HttpRequest $client;

    public function __construct() {
        $this->client = new HttpRequest(Http::photo());
    }

    public function getPhotosOfProducts(array $productIds)
    {
        $response = $this->client->send(
            method:'POST',
            uri:'/photos/batch-loading',
            options: [
                'json' => [
                    'product_ids' => $productIds
                ]
            ],
        );

        $photos = $response;

        return $photos;
    }

    public function getPhotosOfProduct(string $productId)
    {
        $response = $this->client->send(
            method:'GET',
            uri:"/photos?product_id={$productId}",
        );

        $photos = $response->json();

        return $photos;
    }
}