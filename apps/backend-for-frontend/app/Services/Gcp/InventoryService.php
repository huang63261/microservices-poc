<?php

namespace App\Services\Gcp;

use App\Services\Contracts\InventoryService as InvetoryServiceContract;
use App\Services\Http\GoogleCloudApiRequest;

class InventoryService implements InvetoryServiceContract
{
    public function __construct(
        protected GoogleCloudApiRequest $client
    ) {}

    public function getInventoryOfProducts(array $productIds = []): array
    {
        return $this->client->send(
            method:'POST',
            uri:'/api/inventories/batch-loading',
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
            uri:"/api/inventories/{$productId}",
        );
    }
}