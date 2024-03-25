<?php

namespace Tests\Unit;

use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the index method.
     */
    public function test_index_method_returns_all_orders()
    {
        Order::factory()->count(5)->create();

        $this->get(route('orders.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'customer_id',
                        'total_price',
                        'status',
                        'created_at',
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
     * Test the index method with query parameters.
     */
    public function test_index_method_returns_filtered_orders()
    {
        $order1 = Order::factory()->create(['customer_id' => 1]);
        $order2 = Order::factory()->create(['customer_id' => 2]);

        $this->get(route('orders.index', ['customer_id' => 1]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    [
                        'customer_id' => $order1->customer_id,
                    ],
                ],
            ])
            ->assertJsonMissing([
                $order2->toArray(),
            ]);
    }

    /**
     * Test the store method.
     */
    public function test_store_method_creates_a_new_order()
    {
        $data = [
            'customer_id' => 1,
            'total_price' => 1000,
        ];

        $this->post(route('orders.store'), $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => $data
            ]);

        $this->assertDatabaseHas('orders', $data);
    }

    /**
     * Test the show method.
     */
    public function test_show_method_returns_the_order()
    {
        $order = Order::factory()->create();

        $response = $this->get(route('orders.show', $order->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => $order->toArray()
            ]);
    }

    /**
     * Test the update method.
     */
    public function test_update_method_updates_the_order()
    {
        $order = Order::factory()->create();

        $data = [
            'customer_id' => 2,
            'total_price' => 1999,
        ];

        $this->put(route('orders.update', $order->id), $data)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => $data
            ]);

        $this->assertDatabaseHas('orders', array_merge(['id' => $order->id], $data));
    }

    /**
     * Test the destroy method.
     */
    public function test_destroy_method_deletes_the_order()
    {
        $order = Order::factory()->create();

        $this->delete(route('orders.destroy', $order->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
