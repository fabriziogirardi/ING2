<?php

namespace Database\Factories;

use App\Models\CancelPolicy;
use Illuminate\Database\Eloquent\Factories\Factory;

class CancelPolicyFactory extends Factory
{
    protected $model = CancelPolicy::class;

    public function definition(): array
    {
        return [
            'name'                    => $this->faker->unique()->word(),
            'requires_amount_input'   => $this->faker->boolean(),
        ];
    }
}
