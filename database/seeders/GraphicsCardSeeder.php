<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GraphicsCard;

class GraphicsCardSeeder extends Seeder
{
    public function run()
    {
        GraphicsCard::insert([
            [
                'brand' => 'NVIDIA',
                'model' => 'GeForce RTX 3060',
                'memory_size' => 12,
            ],
            [
                'brand' => 'AMD',
                'model' => 'Radeon RX 6600',
                'memory_size' => 8,
            ],
        ]);
    }
}
