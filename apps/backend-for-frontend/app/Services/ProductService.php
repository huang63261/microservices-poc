<?php

namespace App\Services;

use App\Services\Http\HttpRequest;
use Illuminate\Support\Facades\Http;

class ProductService
{
    protected HttpRequest $client;

    public function __construct() {
        $this->client = new HttpRequest(Http::product());
    }

    public function getAll(array $queryParams = [])
    {
        // $token = $this->identityTokenService->generateToken(config('services.ms_product.base_url'));

        return $this->client->send(
            method:'GET',
            uri:'/products-browse',
            // options: [
            //     'headers' => [
            //         'Authorization' => 'Bearer '. $token,
            //     ],
            // ],
            queryParams: $queryParams
        );
    }

    public function getOne(string $productId)
    {
        return $this->client->send(
            method:'GET',
            uri:"/products/{$productId}",
        );
    }
}