<?php

namespace Database\Factories;

use App\Models\Wishlist;
use App\Models\WishlistSublist;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistSublistFactory extends Factory
{
    protected $model = WishlistSublist::class;

    public function definition()
    {
        return [
            'wishlist_id' => Wishlist::factory(),
            'name'        => $this->faker->words(3, true),
        ];
    }
}
