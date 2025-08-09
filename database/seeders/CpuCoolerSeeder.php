<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CpuCooler;

class CpuCoolerSeeder extends Seeder
{
    public function run()
    {
        CpuCooler::insert([
            [
                'brand' => 'Cooler Master',
                'model' => 'Hyper 212 Black Edition',
                'type' => 'Air',
            ],
            [
                'brand' => 'Noctua',
                'model' => 'NH-D15',
                'type' => 'Air',
            ],
        ]);
    }
}
