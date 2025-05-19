<?php

namespace Database\Seeders;

use App\Models\ProductBrand;
use App\Models\ProductModel;
use Illuminate\Database\Seeder;

class ProductModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = ProductBrand::all()->pluck('id')->toArray();

        ProductModel::factory()
            ->count(100)
            ->create();
    }
}
