<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PowerSupply;

class PowerSupplySeeder extends Seeder
{
    public function run()
    {
        PowerSupply::insert([
            [
                'brand' => 'Corsair',
                'model' => 'RM750x',
                'wattage' => 750,
            ],
            [
                'brand' => 'EVGA',
                'model' => 'SuperNOVA 650 G5',
                'wattage' => 650,
            ],
        ]);
    }
}
