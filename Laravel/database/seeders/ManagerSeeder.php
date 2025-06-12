<?php

namespace Database\Seeders;

use App\Models\GovernmentIdType;
use App\Models\Manager;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $person = Person::create([
            'first_name'            => 'Mario',
            'last_name'             => 'Bro',
            'email'                 => 'fabro.la22@gmail.com',
            'birth_date'            => now()->subYears(30)->format('Y-m-d'),
            'government_id_type_id' => GovernmentIdType::where('name', 'DNI')->first()->id,
            'government_id_number'  => '12345678',
        ]);

        Manager::create([
            'person_id' => $person->id,
            'password'  => Hash::make('aguanteluigi'),
        ]);
    }
}
