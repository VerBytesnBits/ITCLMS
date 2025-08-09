<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Motherboard;

class MotherboardSeeder extends Seeder
{
    public function run()
    {
        Motherboard::insert([
            [
                'brand' => 'ASUS',
                'model' => 'ROG STRIX B550-F',
                'form_factor' => 'ATX',
            ],
            [
                'brand' => 'MSI',
                'model' => 'B450 Tomahawk',
                'form_factor' => 'ATX',
            ],
        ]);
    }
}
