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
            'title' => 'Facebook',
            'text'  => 'https://www.facebook.com/alkilar.com.ar/',
        ]);
        FooterElement::create([
            'icon'  => 'fab-square-x-twitter',
            'title' => 'Twitter',
            'text'  => 'https://x.com/alkilarcomar',
        ]);
        FooterElement::create([
            'icon'  => 'fab-square-whatsapp',
            'title' => 'WhatsApp',
            'text'  => 'https://wa.me/+549221xxxxxxx',
        ]);
        FooterElement::create([
            'icon'  => 'fas-square-phone',
            'title' => 'WhatsApp',
            'text'  => '+54 9 221 xxx-xxxx ',
        ]);
    }
}
