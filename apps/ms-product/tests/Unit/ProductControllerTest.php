<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test case for showing all products.
     *
     * @return void
     */
    public function test_index_returns_all_products()
    {
        ProductCategory::factory(10)->create();

        Product::factory(10)->create();

        $this->get(route('products.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'category_id',
                        'price',
                        'status',
                        'description',
                        'category',
                    ],
                ],
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page',
                    'last_page',
                    'from',
                    'to',
                    'path',
                    'per_page',
                    'total',
                    'links',
                ],
            ]);
    }

    /**
     * Test case to verify that the store method creates a new product.
     *
     * @return void
     */
    public function test_store_creates_a_new_product()
    {
        ProductCategory::factory(10)->create();

        $productData = [
            'name' => 'Test Product',
            'category_id' => ProductCategory::all()->random()->id,
            'price' => 100,
            'status' => 1,
            'description' => 'Test product description',
        ];

        $this->post(route('products.store'), $productData)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson($productData);

        $this->assertDatabaseHas('products', $productData);
    }

    /**
     * Test case for showing a product.
     *
     * @return void
     */
    public function test_show_returns_a_specific_product()
    {
        ProductCategory::factory(10)->create();

        $product = Product::factory()->create();

        $this->get(route('products.show', $product))
            ->assertStatus(Response::HTTP_OK);
    }

    /**
     * Test case for updating a product.
     *
     * @return void
     */
    public function test_update_updates_a_specific_product()
    {
        ProductCategory::factory(10)->create();

        $product = Product::factory()->create();

        $updatedProductData = [
            'name' => 'Updated Test Product',
            'category_id' => ProductCategory::all()->random()->id,
            'price' => 150,
            'status' => 1,
            'description' => 'Updated test product description',
        ];

        $this->put(route('products.update', $product), $updatedProductData)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson($updatedProductData);

        $this->assertDatabaseHas('products', array_merge(['id' => $product->id], $updatedProductData));
    }

    /**
     * Test case for deleting a product.
     *
     * @return void
     */
    public function test_destroy_deletes_a_specific_product()
    {
        ProductCategory::factory(10)->create();

        $product = Product::factory()->create();

        $this->delete(route('products.destroy', $product))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertDatabaseMissing('products', $product->toArray());
    }
}