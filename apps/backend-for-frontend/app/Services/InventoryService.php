<?php

namespace App\Services;

use App\Services\Contracts\InventoryService as InvetoryServiceContract;
use App\Services\Http\HttpRequest;
use Illuminate\Support\Facades\Http;

class InventoryService implements InvetoryServiceContract
{
    public function __construct(
        protected HttpRequest $client
    ) {}

    public function getInventoryOfProducts(array $productIds = []): array
    {
        return $this->client->send(
            method:'POST',
            uri:'/inventories/batch-loading',
            options: [
                'json' => [
                    'product_ids' => $productIds,
                ],
            ]
        );
    }

    public function getInventoryOfProduct(string $productId): array
    {
        return $this->client->send(
            method:'GET',
            uri:"/inventories/{$productId}",
        );
    }
}