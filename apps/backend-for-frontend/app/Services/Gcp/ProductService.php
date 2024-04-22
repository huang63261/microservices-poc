<?php

namespace App\Services\Gcp;

use App\Services\Contracts\ProductService as ProductServiceContract;
use App\Services\Http\GoogleCloudApiRequest;

class ProductService implements ProductServiceContract
{
    public function __construct(
        protected GoogleCloudApiRequest $client
    ) {}

    public function getAll(array $queryParams = []): array
    {
        return $this->client->send(
            method:'GET',
            uri:'/api/products-browse',
            options: [
                'query' => $queryParams,
            ]
        );
    }

    public function getOne(string $productId): array
    {
        return $this->client->send(
            method:'GET',
            uri:"/api/products/{$productId}",
        );
    }
}