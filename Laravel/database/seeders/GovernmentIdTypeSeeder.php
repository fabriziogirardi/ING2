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
        $types = [
            ['name' => 'DNI'],
            ['name' => 'PAS'],
            ['name' => 'LE'],
            ['name' => 'LC'],
            ['name' => 'CI'],
        ];

        collect($types)->each(function ($type) {
            GovernmentIdType::create($type);
        });
    }
}
