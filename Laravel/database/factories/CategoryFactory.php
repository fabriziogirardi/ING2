<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_name'        => $this->faker->word(),
            'category_description' => $this->faker->sentence(),
            'parent_id'            => $this->getRandomParentId(),
        ];
    }

    private function getRandomParentId(): ?int
    {
        return (rand() % 2 == 0 && Category::count() > 1) ? Category::all()->random()->id : null;
    }
}
