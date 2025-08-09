<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Memory;

class MemorySeeder extends Seeder
{
    public function run()
    {
        Memory::insert([
            [
                'brand' => 'Corsair',
                'type' => 'DDR4',
                'capacity' => 16,
            ],
            [
                'brand' => 'G.Skill',
                'type' => 'DDR4',
                'capacity' => 8,
            ],
        ]);
    }
}
