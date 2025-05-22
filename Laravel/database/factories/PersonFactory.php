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
            'name'                  => $this->faker->name(),
            'lastname'              => $this->faker->lastName(),
            'email'                 => $this->faker->unique()->safeEmail(),
            'government_id_type_id' => GovernmentIdTypeFactory::new()->create(),
            'government_id_number'  => $this->faker->unique()->numerify('#########'),
        ];
    }
}
