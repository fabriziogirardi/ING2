<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\GovernmentIdType;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $person = Person::create([
            'first_name'            => 'Carlos',
            'last_name'             => 'Gomez',
            'email'                 => 'carlos@gmail.com',
            'birth_date'            => now()->subYears(28)->format('Y-m-d'),
            'government_id_type_id' => GovernmentIdType::where('name', 'DNI')->first()->id,
            'government_id_number'  => '98765432',
        ]);

        Customer::create([
            'person_id' => $person->id,
            'password'  => Hash::make('123456'),
        ]);
    }
}
