<?php

namespace App\Services;

use App\Services\Contracts\PhotoService as PhotoServiceContract;
use App\Services\Http\HttpRequest;
use Illuminate\Support\Facades\Http;

class PhotoService implements PhotoServiceContract
{
    public function __construct(
        protected HttpRequest $client
    ) {}

    public function getPhotosOfProducts(array $productIds): array
    {
        return $this->client->send(
            method:'POST',
            uri:'/photos/batch-loading',
            options: [
                'json' => [
                    'product_ids' => $productIds
                ]
            ],
        );
    }

    public function getPhotosOfProduct(string $productId): array
    {
        return $this->client->send(
            method:'GET',
            uri:"/photos?product_id={$productId}",
        );
    }
}