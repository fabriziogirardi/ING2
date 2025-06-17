<?php

namespace Database\Factories;

use App\Models\WishlistItem;
use App\Models\WishlistSublist;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistItemFactory extends Factory
{
    protected $model = WishlistItem::class;

    public function definition()
    {
        $start = $this->faker->dateTimeBetween('now', '+30 days');
        $end   = (clone $start)->modify('+'.mt_rand(1, 7).' days');

        return [
            'wishlist_sublist_id' => WishlistSublist::factory(),
            'machine_id'          => Product::factory(),
            'start_date'          => $start->format('Y-m-d'),
            'end_date'            => $end->format('Y-m-d'),
        ];
    }
}

