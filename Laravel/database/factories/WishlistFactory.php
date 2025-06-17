<?php

// database/factories/WishlistFactory.php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistFactory extends Factory
{
    protected $model = Wishlist::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'name'    => $this->faker->word(),
        ];
    }
}
