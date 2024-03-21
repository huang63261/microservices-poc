<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ProductReview;

class ProductReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list()
    {
        $productReviews = ProductReview::factory()->count(3)->create();

        $response = $this->get(route('product-reviews.index'));

        $response->assertStatus(200);
        $response->assertJson($productReviews->toArray());
    }

    public function test_can_create()
    {
        $productReviewData = [
            'product_id' => 1,
            'customer_id' => 1,
            'rating' => 5,
            'content' => 'Great product!',
        ];

        $response = $this->post(route('product-reviews.store', $productReviewData));

        $response->assertStatus(201);
        $response->assertJson($productReviewData);
    }

    public function test_can_show()
    {
        $productReview = ProductReview::factory()->create();

        $response = $this->get(route('product-reviews.show', $productReview->id));

        $response->assertStatus(200);
        $response->assertJson($productReview->toArray());
    }

    public function test_can_update()
    {
        $productReview = ProductReview::factory()->create();
        $updatedProductReviewData = [
            'rating' => 4,
            'content' => 'Good product!',
        ];

        $response = $this->put(route('product-reviews.update', $productReview->id), $updatedProductReviewData);

        $response->assertStatus(200);
        $response->assertJson($updatedProductReviewData);
    }
}
