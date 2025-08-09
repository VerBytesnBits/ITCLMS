<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HardDiskDrive;

class HardDiskDriveSeeder extends Seeder
{
    public function run()
    {
        HardDiskDrive::insert([
            [
                'brand'    => 'Seagate',
                'model'    => 'Barracuda',
                'capacity' => 2000,
                'type'     => 'HDD',
            ],
            [
                'brand'    => 'WD',
                'model'    => 'Blue',
                'capacity' => 1000,
                'type'     => 'HDD',
            ],
        ]);
    }
}
