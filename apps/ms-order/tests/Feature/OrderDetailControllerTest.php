<?php

namespace Tests\Feature;

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
}