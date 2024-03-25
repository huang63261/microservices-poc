<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => $this->faker->randomNumber(),
            'total_price' => $this->faker->numberBetween(1, 16777215),
            'status' => $this->faker->randomElement([0, 1, 2, 3]),
        ];
    }
}
