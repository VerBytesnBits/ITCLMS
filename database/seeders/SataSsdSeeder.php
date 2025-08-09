<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SataSsd;

class SataSsdSeeder extends Seeder
{
    public function run()
    {
        SataSsd::insert([
            [
                'brand'    => 'Crucial',
                'model'    => 'MX500',
                'capacity' => 1000,
                'type'     => 'SATA SSD',
            ],
            [
                'brand'    => 'Samsung',
                'model'    => '860 EVO',
                'capacity' => 500,
                'type'     => 'SATA SSD',
            ],
        ]);
    }
}
