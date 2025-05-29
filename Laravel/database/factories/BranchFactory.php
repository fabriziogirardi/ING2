<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'place_id'    => $this->faker->unique()->uuid(),
            'name'        => $this->faker->name(),
            'address'     => $this->faker->address(),
            'description' => $this->faker->text(),
            'latitude'    => $this->faker->latitude(),
            'longitude'   => $this->faker->longitude(),
        ];
    }
}
