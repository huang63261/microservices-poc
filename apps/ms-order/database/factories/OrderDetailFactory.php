<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory()->create()->id,
            'product_id' => $this->faker->unique()->randomNumber(5),
            'product_name' => $this->faker->word(),
            'price' => $this->faker->randomNumber(4),
            'quantity' => $this->faker->numberBetween(1, 65535),
        ];
    }
}
