<?php

namespace App\Services\Gcp;

use App\Services\Contracts\PhotoService as PhotoServiceContract;
use App\Services\Http\GoogleCloudApiRequest;

class PhotoService implements PhotoServiceContract
{
    public function __construct(
        protected GoogleCloudApiRequest $client
    ) {}

    public function getPhotosOfProducts(array $productIds): array
    {
        return $this->client->send(
            method:'POST',
            uri:'/api/photos/batch-loading',
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
            uri:"/api/photos?product_id={$productId}",
        );
    }
}