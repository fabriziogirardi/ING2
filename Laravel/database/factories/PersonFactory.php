<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name'            => $this->faker->name(),
            'last_name'             => $this->faker->lastName(),
            'email'                 => $this->faker->unique()->safeEmail(),
            'government_id_type_id' => GovernmentIdTypeFactory::new()->create(),
            'government_id_number'  => $this->faker->unique()->numerify('#########'),
            'birth_date'            => $this->faker->date(),
        ];
    }

    public function adult(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'birth_date' => now()->subYears(20)->format('Y-m-d'),
            ];
        });
    }
}
