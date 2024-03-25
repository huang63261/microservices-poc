<?php

namespace Tests\Unit;

use App\Models\Inventory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the index method.
     */
    public function test_index_method_returns_all_inventories()
    {
        $inventories = Inventory::factory()->count(5)->create();

        $response = $this->get(route('inventories.index'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson(array_values($inventories->sort()->toArray()))
            ->assertJsonStructure([
                '*' => [
                    'product_id',
                    'locked_quantity',
                    'available_quantity',
                    'total_quantity',
                ],
            ]);
    }

    /**=
     * Test the store method.
     */
    public function test_store()
    {
        $data = [
            'product_id' => $this->faker->unique()->randomNumber(),
            'locked_quantity' => $lockedQuantity = $this->faker->numberBetween(0, 65535),
            'available_quantity' => $availableQuantity = $this->faker->numberBetween(0, 65535),
            'total_quantity' => $lockedQuantity + $availableQuantity,
        ];

        $response = $this->post(route('inventories.store'), $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson($data);
    }

    /**
     * Test the show method.
     */
    public function test_show()
    {
        $inventory = Inventory::factory()->create();

        $response = $this->get(route('inventories.show', $inventory->product_id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($inventory->toArray());
    }

    /**
     * Test the update method.
     */
    public function test_update()
    {
        $inventory = Inventory::factory()->create();

        $data = [
            'product_id' => $this->faker->unique()->randomNumber(),
            'locked_quantity' => $lockedQuantity = $this->faker->numberBetween(0, 65535),
            'available_quantity' => $availableQuantity = $this->faker->numberBetween(0, 65535),
            'total_quantity' => $lockedQuantity + $availableQuantity,
        ];

        $response = $this->put(route('inventories.update', $inventory->product_id), $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($data);
    }

    /**
     * Test the destroy method.
     */
    public function test_destroy()
    {
        $inventory = Inventory::factory()->create();

        $response = $this->delete(route('inventories.destroy', $inventory->product_id));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}