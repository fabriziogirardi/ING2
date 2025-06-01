<?php

namespace Database\Seeders;

use App\Models\GovernmentIdType;
use Illuminate\Database\Seeder;

class GovernmentIdTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GovernmentIdType::factory()->count(5)->create();
    }
}
