<?php

namespace App\Services;

use App\Services\Http\AbstractHttpRequest;
use Illuminate\Support\Facades\Http;

class ProductService extends AbstractHttpRequest
{
    public function __construct() {
        $this->http = Http::product();
    }

    public function getProductsByIds(array $productIds)
    {
        $response = $this->send('GET', '/products', [
            'query' => [
                'product_id' => $productIds ?? []
            ]
        ]);

        return $response;
    }

    public function getPricing(array $items)
    {
        $response = $this->send('POST', '/pricing', [
            'json' => [
                'items' => $items
            ]
        ]);

        return $response;
    }
}