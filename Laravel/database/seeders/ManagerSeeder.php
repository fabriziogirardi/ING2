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
        Person::create([
            'first_name'            => 'Mario',
            'last_name'             => 'Bro',
            'email'                 => 'fabro.la22@gmail.com',
            'birth_date'            => now()->subYears(30)->format('Y-m-d'),
            'government_id_type_id' => GovernmentIdType::where('name', 'DNI')->first()->id,
            'government_id_number'  => '12345678',
        ])->manager()->create([
            'password'  => Hash::make('aguanteluigi'),
        ]);
        
        Person::create([
            'first_name'            => 'Mario',
            'last_name'             => 'Bro',
            'email'                 => 'matias.abrego@hotmail.com',
            'birth_date'            => now()->subYears(30)->format('Y-m-d'),
            'government_id_type_id' => GovernmentIdType::where('name', 'DNI')->first()->id,
            'government_id_number'  => '22345678',
        ])->manager()->create([
            'password'  => Hash::make('aguanteluigi'),
        ]);
        
        Person::create([
            'first_name'            => 'Mario',
            'last_name'             => 'Bro',
            'email'                 => 'mateosuarez1905@hotmail.com',
            'birth_date'            => now()->subYears(30)->format('Y-m-d'),
            'government_id_type_id' => GovernmentIdType::where('name', 'DNI')->first()->id,
            'government_id_number'  => '32345678',
        ])->manager()->create([
            'password' => Hash::make('aguanteluigi'),
        ]);
        
        Person::create([
            'first_name'            => 'Mario',
            'last_name'             => 'Bro',
            'email'                 => 'neftalitosu@gmail.com',
            'birth_date'            => now()->subYears(30)->format('Y-m-d'),
            'government_id_type_id' => GovernmentIdType::where('name', 'DNI')->first()->id,
            'government_id_number'  => '42345678',
        ])->manager()->create([
            'password' => Hash::make('aguanteluigi'),
        ]);
    }
}
