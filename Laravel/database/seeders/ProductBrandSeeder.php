<?php

namespace Database\Seeders;

use App\Models\ProductBrand;
use Illuminate\Database\Seeder;

class ProductBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = ['John Deere', 'Husqvarna', 'Stihl', 'Toro', 'Cub Cadet', 'Makita', 'Bosch', 'MTD', 'Honda Power Equipment', 'Echo', 'Briggs & Stratton', 'Craftsman', 'Snapper', 'Kubota', 'Yanmar', 'Caterpillar', 'Hitachi', 'Komatsu', 'Doosan', 'Fendt', 'Claas', 'New Holland', 'Case IH', 'Bobcat', 'JCB'];

        foreach (array_slice($brands, 0, 20) as $brand) {
            ProductBrand::factory()->create(['name' => $brand]);
        }
    }
}
