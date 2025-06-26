<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // CategorySeeder::class,
            // ProductBrandSeeder::class,
            // ProductModelSeeder::class,
            // ProductSeeder::class,
            BranchSeeder::class,
            GovernmentIdTypeSeeder::class,
            ManagerSeeder::class,
            EmployeeSeeder::class,
            CustomerSeeder::class,
            ProductBrandSeeder::class,
            ProductModelSeeder::class,
            CategorySeeder::class,
            FooterElementSeeder::class,
            CancelPolicySeeder::class,
        ]);
    }
}
