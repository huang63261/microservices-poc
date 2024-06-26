<?php

namespace App\Services;

use App\Services\Contracts\ProductService as ProductServiceContract;
use App\Services\Http\HttpRequest;
use Illuminate\Support\Facades\Http;

class ProductService implements ProductServiceContract
{
    public function __construct(
        protected HttpRequest $client
    ) {}

    public function getAll(array $queryParams = []): array
    {
        return $this->client->send(
            method:'GET',
            uri:'/products-browse',
            options: [
                'query' => $queryParams,
            ]
        );
    }

    public function getOne(string $productId): array
    {
        return $this->client->send(
            method:'GET',
            uri:"/products/{$productId}",
        );
    }
}