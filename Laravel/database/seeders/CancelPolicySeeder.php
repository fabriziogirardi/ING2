<?php

namespace Database\Seeders;

use App\Models\CancelPolicy;
use Illuminate\Database\Seeder;

class CancelPolicySeeder extends Seeder
{
    public function run(): void
    {
        CancelPolicy::create([
            'name'                  => 'Completa',
            'requires_amount_input' => false,
        ]);

        CancelPolicy::create([
            'name'                  => 'Parcial',
            'requires_amount_input' => true,
        ]);
    }
}
