<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\M2Ssd;

class M2SsdSeeder extends Seeder
{
    public function run()
    {
        M2Ssd::insert([
            [
                'brand'    => 'Samsung',
                'model'    => '970 EVO Plus',
                'capacity' => 1000,
                'type'     => 'NVMe',
            ],
            [
                'brand'    => 'WD',
                'model'    => 'Black SN850',
                'capacity' => 500,
                'type'     => 'NVMe',
            ],
        ]);
    }
}
