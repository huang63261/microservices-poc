<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ProductReview;
use Illuminate\Http\Response;

class ProductReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test case to verify that the index method returns all product reviews.
     *
     * @return void
     */
    public function test_index_returns_all_product_reviews()
    {
        $productReviews = ProductReview::factory()->count(3)->create();

        $response = $this->get(route('product-reviews.index'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson($productReviews->toArray());
        $response->assertJsonStructure([
            '*' => [
                'id',
                'product_id',
                'customer_id',
                'rating',
                'content',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    /**
     * Test case to verify that the store method creates a new product review.
     *
     * @return void
     */
    public function test_store_creates_a_new_product_review()
    {
        $productReviewData = [
            'product_id' => 1,
            'customer_id' => 1,
            'rating' => 5,
            'content' => 'Great product!',
        ];

        $response = $this->post(route('product-reviews.store', $productReviewData));

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson($productReviewData);
        $response->assertJsonStructure([
            'id',
            'product_id',
            'customer_id',
            'rating',
            'content',
            'created_at',
            'updated_at',
        ]);

        $this->assertDatabaseHas('product_reviews', $productReviewData);
    }

    /**
     * Test case to verify that the show method returns a specific product review.
     *
     * @return void
     */
    public function test_show_returns_a_specific_product_review()
    {
        $productReview = ProductReview::factory()->create();

        $response = $this->get(route('product-reviews.show', $productReview->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson($productReview->toArray());
        $response->assertJsonStructure([
            'id',
            'product_id',
            'customer_id',
            'rating',
            'content',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * Test case to verify that the update method modifies an existing product review.
     *
     * @return void
     */
    public function test_update_modifies_an_existing_product_review()
    {
        $productReview = ProductReview::factory()->create();
        $updatedProductReviewData = [
            'rating' => 4,
            'content' => 'Good product!',
        ];

        $response = $this->put(route('product-reviews.update', $productReview->id), $updatedProductReviewData);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson($updatedProductReviewData);
        $response->assertJsonStructure([
            'id',
            'product_id',
            'customer_id',
            'rating',
            'content',
            'created_at',
            'updated_at',
        ]);

        $this->assertDatabaseHas('product_reviews', array_merge(['id' => $productReview->id], $updatedProductReviewData));
    }

    /**
     * Test case to verify that the destroy method deletes an existing product review.
     *
     * @return void
     */
    public function test_destroy_deletes_an_existing_product_review()
    {
        $productReview = ProductReview::factory()->create();

        $response = $this->delete(route('product-reviews.destroy', $productReview->id));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
