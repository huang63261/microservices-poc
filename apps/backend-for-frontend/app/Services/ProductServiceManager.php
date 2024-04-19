<?php

namespace App\Services;

use App\Http\Resources\ProductCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class ProductServiceManager
{
    public function __construct(
        protected ProductService $productService,
        protected ReviewService $reviewService,
        protected PhotoService $photoService,
        protected InventoryService $inventoryService,
    ) {}

    public function getAllProducts(array $queryParams)
    {
        $products = $this->productService->getAll(queryParams: $queryParams);

        if (isset($prodcuts['data']) && empty($products['data'])) {
            throw new \Exception(json_encode(['message' => 'Products not found']), Response::HTTP_NOT_FOUND);
        }

        $productsId = collect($products['data'])->pluck('id')->toArray();

        $reviews = $this->reviewService->getReviewsOfProducts($productsId);
        $photos = $this->photoService->getPhotosOfProducts($productsId);
        $inventories = $this->inventoryService->getInventoryOfProducts($productsId);

        // Merge reviews, photos, and inventories into products
        foreach ($products['data'] as &$product) {
            $productId = $product['id'];
            $product['reviews'] = $reviews[$productId] ?? [];
            $product['photos'] = $photos[$productId] ?? [];
            $product['inventory'] = $inventories[$productId] ?? [];
        }

        return new ProductCollection($products);
    }

    /**
     * Get product by id
     *
     * @param string $productId
     */
    public function getProductById(string $productId)
    {
        $product = $this->productService->getOne($productId);
        $reviews = $this->reviewService->getReviewsOfProduct($productId);
        $photos = $this->photoService->getPhotosOfProduct($productId);
        $inventory = $this->inventoryService->getInventoryOfProduct($productId);

        $product['data']['reviews'] = $reviews;
        $product['data']['photos'] = $photos;
        $product['data']['inventory'] = $inventory;

        return response()->json($product);
    }

    /**
     * Get products concurrently by using Http::pool
     *
     * @param string $productId
     */
    public function getProductsConcurrently(string $productId)
    {
        $headers = [
            'Accept' => 'application/json',
        ];

        $product = $this->productService->getOne($productId);

        if (!$product['data']) {
            throw new \Exception(json_encode(['message' => 'Products not found']), Response::HTTP_NOT_FOUND);
        }

        $response = Http::pool(fn ($pool) => [
            $pool->as('reviews')->withHeaders($headers)->get(config('services.ms_review.api_base_url') . "/product-reviews?product_id={$productId}"),
            $pool->as('photos')->withHeaders($headers)->get(config('services.ms_photo.api_base_url') . "/photos?product_id={$productId}"),
            $pool->as('inventory')->withHeaders($headers)->get(config('services.ms_inventory.api_base_url') . "/inventories/{$productId}"),
        ]);

        $product['data']['reviews'] = $response['reviews']->ok() ? $response['reviews']->json() : [];
        $product['data']['photos'] = $response['photos']->ok() ? $response['photos']->json() : [];
        $product['data']['inventory'] = $response['inventory']->ok() ? $response['inventory']->json() : [];

        return $product;
    }
}