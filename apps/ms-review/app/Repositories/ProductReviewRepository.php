<?php

namespace App\Repositories;

use App\Models\ProductReview;

class ProductReviewRepository
{
    protected $productReview;

    /**
     * ProductReviewRepository constructor.
     *
     * @param ProductReview $productReview The product review model instance.
     */
    public function __construct(ProductReview $productReview)
    {
        $this->productReview = $productReview;
    }

    /**
     * Get all product reviews.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        return $this->productReview->all();
    }

    /**
     * Find product reviews by product ID.
     *
     * @param mixed $productId The ID of the product.
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findByProductId($productId)
    {
        return $this->productReview
            ->where('product_id', $productId)
            ->get()
            ->toArray();
    }

    public function findByProductIds(array $productIds)
    {
        return $this->productReview
            ->whereIn('product_id', $productIds)
            ->get()
            ->toArray();
    }

    /**
     * Create a new product review.
     *
     * @param array $data The data for creating the product review.
     * @return \App\Models\ProductReview
     */
    public function create(array $data)
    {
        return $this->productReview->create($data);
    }

    /**
     * Find a product review by its ID.
     *
     * @param mixed $id The ID of the product review.
     * @return \App\Models\ProductReview|null
     */
    public function find($id)
    {
        return $this->productReview->find($id);
    }

    /**
     * Update a product review.
     *
     * @param array $data The data for updating the product review.
     * @param mixed $id The ID of the product review.
     * @return bool
     */
    public function update(array $data, $id)
    {
        if (!$this->productReview->where('id', $id)->update($data)) {
            return false;
        }

        return $this->productReview->find($id);
    }

    /**
     * Delete a product review.
     *
     * @param mixed $id The ID of the product review.
     * @return bool|null
     */
    public function delete($id)
    {
        return $this->productReview->destroy($id);
    }
}
