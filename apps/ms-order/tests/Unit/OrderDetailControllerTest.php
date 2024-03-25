<?php

namespace Tests\Unit;

use App\Models\OrderDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class OrderDetailControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test the index method.
     */
    public function test_index_method_returns_list_of_order_details()
    {
        OrderDetail::factory(5)->create();

        $this->get(route('order-details.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'order_id',
                        'product_id',
                        'product_name',
                        'price',
                        'quantity',
                    ],
                ],
                'links' => [ 'first', 'last', 'prev', 'next' ],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'path',
                    'per_page',
                    'to',
                    'total',
                ]
            ]);
    }

    public function test_index_method_returns_filtered_order_details()
    {
        $orderDetail1 = OrderDetail::factory()->create(['order_id' => 1]);
        $orderDetail2 = OrderDetail::factory()->create(['order_id' => 2]);

        $this->get(route('order-details.index', ['order_id' => 1]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    [
                        'order_id' => $orderDetail1->order_id
                    ]
                ]
            ])
            ->assertJsonMissing([
                $orderDetail2->toArray()
            ]);
    }

    /**
     * Test the store method.
     */
    public function test_store_method_creates_a_new_order_detail()
    {
        $data = [
            'order_id' => $this->faker->numberBetween(1, 10),
            'product_id' => $this->faker->numberBetween(1, 10),
            'product_name' => $this->faker->word,
            'price' => $this->faker->numberBetween(100, 1000),
            'quantity' => $this->faker->numberBetween(1, 10),
        ];

        $this->post(route('order-details.store'), $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => $data,
            ]);

        $this->assertDatabaseHas('order_details', $data);
    }

    /**
     * Test the show method.
     */
    public function test_show()
    {
        $orderDetail = OrderDetail::factory()->create();

        $response = $this->get(route('order-details.show', $orderDetail->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $orderDetail->id,
                    'order_id' => $orderDetail->order_id,
                    'product_id' => $orderDetail->product_id,
                    'product_name' => $orderDetail->product_name,
                    'price' => $orderDetail->price,
                    'quantity' => $orderDetail->quantity,
                ],
            ]);
    }

    /**
     * Test the update method.
     */
    public function test_update()
    {
        $orderDetail = OrderDetail::factory()->create();

        $data = [
            'order_id' => $this->faker->numberBetween(1, 10),
            'product_id' => $this->faker->numberBetween(1, 10),
            'product_name' => $this->faker->word,
            'price' => $this->faker->numberBetween(100, 1000),
            'quantity' => $this->faker->numberBetween(1, 10),
        ];

        $this->put(route('order-details.update', $orderDetail->id), $data)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => $data,
            ]);
    }

    /**
     * Test the destroy method.
     */
    public function test_destroy()
    {
        $orderDetail = OrderDetail::factory()->create();

        $this->delete(route('order-details.destroy', $orderDetail->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('order_details', ['id' => $orderDetail->id]);
    }
}