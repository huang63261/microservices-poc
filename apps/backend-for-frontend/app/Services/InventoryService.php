<?php

namespace App\Services;

use App\Services\Http\HttpRequest;
use Illuminate\Support\Facades\Http;

class InventoryService
{
    protected HttpRequest $client;

    public function __construct() {
        $this->client = new HttpRequest(Http::inventory());
    }

    public function getInventoryOfProducts(array $productIds = [])
    {
        $inventories = $this->client->send(
            method:'POST',
            uri:'/inventories/batch-loading',
            options: [
                'json' => [
                    'product_ids' => $productIds,
                ],
            ]
        );

        return $inventories;
    }

    public function getInventoryOfProduct(string $productId)
    {
        $inventory = $this->client->send(
            method:'GET',
            uri:"/inventories/{$productId}",
        );

        return $inventory;
    }
}