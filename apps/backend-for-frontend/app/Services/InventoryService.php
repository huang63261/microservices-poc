<?php

namespace App\Services;

use App\Services\Http\AbstractHttpRequest;
use Illuminate\Support\Facades\Http;

class InventoryService extends AbstractHttpRequest
{
    public function __construct() {
        $this->http = Http::inventory();
    }

    public function getInventoryOfProducts(array $productIds = [])
    {
        $inventories = $this->send(
            method:'POST',
            uri:'/inventories/batch-loading',
            options: [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'form_params' => [
                    'product_ids' => $productIds,
                ],
            ]
        );

        return $inventories;
    }

    public function getInventoryOfProduct(string $productId)
    {
        $inventory = $this->send(
            method:'GET',
            uri:"/inventories/{$productId}",
            options: [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]
        );

        return $inventory;
    }
}