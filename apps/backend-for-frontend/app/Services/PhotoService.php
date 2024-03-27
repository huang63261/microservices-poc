<?php

namespace App\Services;

use App\Services\Http\AbstractHttpRequest;
use Illuminate\Support\Facades\Http;

class PhotoService extends AbstractHttpRequest
{
    public function __construct() {
        $this->http = Http::photo();
    }

    public function getPhotosOfProducts(array $productIds)
    {
        // $responses = Http::pool(fn ($pool) => collect($productIds)->each(function ($productId) use ($pool) {
        //     $pool->get(config("services.ms_photo.api_base_url") . "photos?product_id={$productId}", [
        //         'headers' => [
        //             'Accept' => 'application/json',
        //         ],
        //     ]);
        // }));

        // foreach ($productIds as $index => $productId) {
        //     $photos[$productId] = $responses[$index]->json();
        // }

        $response = $this->http->send(
            method:'POST',
            url:'/photos/batch-loading',
            options: [
                'headers' => [
                    'Accept' => 'application/json',
                ],
                'form_params' => [
                    'product_ids' => $productIds
                ]
            ],
        );

        $photos = $response->json();

        return $photos;
    }

    public function getPhotosOfProduct(string $productId)
    {
        $response = $this->http->send(
            method:'GET',
            url:"/photos?product_id={$productId}",
            options: [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ],
        );

        $photos = $response->json();

        return $photos;
    }
}