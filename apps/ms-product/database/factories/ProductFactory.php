<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'category_id' => ProductCategory::all()->random()->id,
            'price' => $this->faker->numberBetween(1, 10000),
            'status' => $this->faker->randomElement([0, 1, 2]),
            'description' => $this->faker->text(),
        ];
    }
}
