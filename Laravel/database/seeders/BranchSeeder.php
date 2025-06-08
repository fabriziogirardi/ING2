<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::create([
            'place_id'    => 'EkBDLiAxNiAxNDI4LCBCMTkwNCBMYSBQbGF0YSwgUHJvdmluY2lhIGRlIEJ1ZW5vcyBBaXJlcywgQXJnZW50aW5hIjESLwoUChIJL9BLIp3oopUR0L91FHhjWn8QlAsqFAoSCfuoA7Qy5qKVEWz6_s4J8Fzs',
            'name'        => 'Casa central',
            'address'     => 'C. 16 1428, B1904 La Plata, Provincia de Buenos Aires, Argentina',
            'latitude'    => -34.9305203,
            'longitude'   => -57.948863,
            'description' => 'Casa central de la empresa, en ella se pueden encontrar los gerentes.',
        ]);
        Branch::create([
            'place_id'    => 'ChIJRUJJWjHmopURBl1sieP6g4o',
            'name'        => 'Sucursal centro',
            'address'     => 'C. 47 774, B1900AKH La Plata, Provincia de Buenos Aires, Argentina',
            'latitude'    => -34.9163913,
            'longitude'   => -57.9555698,
            'description' => 'Sucursal principal, es la única que cuenta con envío a domicilio.',
        ]);
    }
}
