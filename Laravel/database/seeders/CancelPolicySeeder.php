<?php

namespace Database\Seeders;

use App\Models\CancelPolicy;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CancelPolicySeeder extends Seeder
{
    public function run(): void
    {
        CancelPolicy::factory()->create([
            'name'                  => 'Completa',
            'requires_amount_input' => false,
        ]);

        CancelPolicy::factory()->create([
            'name'                  => 'Parcial',
            'requires_amount_input' => true,
        ]);
    }
}
