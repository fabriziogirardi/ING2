<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductModel;
use Illuminate\Database\Seeder;
use Random\RandomException;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws RandomException
     */
    public function run(): void
    {
        //
        //        for ($index = 0; $index < 100; $index++) {
        //            Product::factory()
        //                ->hasAttached(Category::inRandomOrder()->first())
        //                ->create();
        //        }

        $brands = ['Caterpillar', 'Komatsu', 'Hitachi', 'Volvo', 'Doosan', 'John Deere', 'JCB', 'Case', 'Bobcat', 'Kubota', 'Crown', 'Toro', 'Honda', 'Generac', 'Mercedes-Benz', 'Genie', 'JLG'];
        $models = ['320D', 'PC210', 'ZX210LC', 'EW160E', 'DX140LCR', '310L', '3CX', '580N', 'S650', 'SVL75', 'CB24B', 'DD25B', '1930ES', 'GS-3246', 'CM125', 'MMX-650', 'EU2200i', 'GP6500', 'FMX', 'Arocs'];

        foreach (Category::all() as $category) {
            for ($i = 1; $i <= random_int(1, 4); $i++) {
                $brandName = $brands[array_rand($brands)];
                $modelName = $models[array_rand($models)];

                $brand = ProductBrand::firstOrCreate(['name' => $brandName]);
                $model = ProductModel::firstOrCreate([
                    'product_brand_id' => $brand->id,
                    'name'             => $modelName,
                ]);

                $product = \App\Models\Product::create([
                    'name'             => "$brandName $modelName #$i",
                    'description'      => fake()->sentence(),
                    'product_model_id' => $model->id,
                    'price'            => fake()->randomFloat(2, 100, 5000),
                    'min_days'         => random_int(1, 5),
                ]);

                $product->categories()->attach($category->id);
            }
        }
    }
}
