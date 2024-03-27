<?php

namespace App\Services;

use App\Services\Http\AbstractHttpRequest;
use Illuminate\Support\Facades\Http;

class ProductService extends AbstractHttpRequest
{
    public function __construct() {
        $this->http = Http::product();
    }

    public function getAll(array $queryParams = [])
    {
        return $this->send(
            method:'GET',
            uri:'/products',
            options: [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ],
            queryParams: $queryParams
        );
    }

    public function getOne(string $productId)
    {
        return $this->send(
            method:'GET',
            uri:"/products/{$productId}",
            options: [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]
        );
    }
}