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
            uri:'/products-browse',
            queryParams: $queryParams
        );
    }

    public function getOne(string $productId)
    {
        return $this->send(
            method:'GET',
            uri:"/products/{$productId}",
        );
    }
}