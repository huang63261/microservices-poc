<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->unique()->randomNumber(),
            'locked_quantity' => $locked_quantity = $this->faker->numberBetween(0, 20000),
            'available_quantity' => $available_quantity = $this->faker->numberBetween(0, 20000),
            'total_quantity' => $locked_quantity + $available_quantity,
        ];
    }
}
