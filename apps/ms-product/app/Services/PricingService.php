<?php

namespace App\Services;

use App\Repositories\ProductRepository;

class PricingService
{
    public function __construct(
        protected ProductRepository $productRepository
    ) {}

    /**
     * Calculate the total price of the given items.
     *
     * @param array $items
     * @return float
     */
    public function calculate(array $items)
    {
        $products = $this->productRepository->findManyByIds(
            collect($items)->pluck('product_id')->toArray()
        );

        $price = 0;

        foreach ($items as $item) {
            $product = $products->firstWhere('id', $item['product_id']);

            $price += $product->price * $item['quantity'];
        }

        return $price;
    }
}