<?php

namespace Tests\Feature;

use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test case to check if index method returns product categories.
     *
     * @return void
     */
    public function test_index_method_returns_product_categories()
    {
        $productCategories = ProductCategory::factory()->count(5)->create();

        $response = $this->get(route('product-categories.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($productCategories->toArray());
    }

    /**
     * Test case to check if store method creates product category.
     *
     * @return void
     */
    public function test_store_method_creates_product_category()
    {
        $data = [
            'name' => 'Test Category',
        ];

        $this->post(route('product-categories.store'), $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson($data);

        $this->assertDatabaseHas('product_categories', $data);
    }

    /**
     * Test case to check if show method returns product category.
     *
     * @return void
     */
    public function test_show_method_returns_product_category()
    {
        $productCategory = ProductCategory::factory()->create();

        $this->get(route('product-categories.show', $productCategory))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($productCategory->toArray());
    }

    /**
     * Test case to check if update method updates product category.
     *
     * @return void
     */
    public function test_update_method_updates_product_category()
    {
        $productCategory = ProductCategory::factory()->create();

        $data = [
            'name' => 'Updated Category',
        ];

        $this->put(route('product-categories.update', $productCategory), $data)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($data);

        $this->assertDatabaseHas('product_categories', $data);
    }

    /**
     * Test case to check if destroy method deletes product category.
     *
     * @return void
     */
    public function test_destroy_method_deletes_product_category()
    {
        $productCategory = ProductCategory::factory()->create();

        $this->delete(route('product-categories.destroy', $productCategory))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('product_categories', $productCategory->toArray());
    }

}
