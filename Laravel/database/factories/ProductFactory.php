<?php

namespace Database\Factories;

use App\Models\ProductModel;
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
            'name'             => $this->faker->name(),
            'description'      => $this->faker->text(),
            'product_model_id' => ProductModel::factory(),
            'price'            => $this->faker->randomFloat(2, 0, 900000),
        ];
    }
}
