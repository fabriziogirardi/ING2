<?php

namespace Database\Seeders;

use App\Models\FooterElement;
use Illuminate\Database\Seeder;

class FooterElementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FooterElement::create([
            'icon'  => 'fab-square-facebook',
            'link'  => 'https://www.facebook.com/alkilar.com.ar/',
        ]);
        FooterElement::create([
            'icon'  => 'fab-square-x-twitter',
            'link'  => 'https://x.com/alkilarcomar',
        ]);
    }
}
