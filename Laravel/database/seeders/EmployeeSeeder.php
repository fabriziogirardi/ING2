<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\GovernmentIdType;
use App\Models\Person;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $person = Person::create([
            'first_name'            => 'Juan',
            'last_name'             => 'Perez',
            'email'                 => 'juan@gmail.com',
            'birth_date'            => now()->subYears(25)->format('Y-m-d'),
            'government_id_type_id' => GovernmentIdType::where('name', 'DNI')->first()->id,
            'government_id_number'  => '11222333',
        ]);

        Employee::create([
            'person_id' => $person->id,
            'password'  => Hash::make('123456'),
        ]);
    }
}
