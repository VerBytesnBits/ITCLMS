<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Processor;

class ProcessorSeeder extends Seeder
{
    public function run()
    {
        Processor::insert([
            [
                'brand' => 'Intel',
                'model' => 'Core i5-4460',
                'base_clock' => 3.2,   // GHz
                'boost_clock' => 3.4,  // GHz
                'cores' => 4,
                'threads' => 4,
            ],
            [
                'brand' => 'AMD',
                'model' => 'Ryzen 5 5600X',
                'base_clock' => 3.7,   // GHz
                'boost_clock' => 4.6,  // GHz
                'cores' => 6,
                'threads' => 12,
            ],
            [
                'brand' => 'Intel',
                'model' => 'Core i7-10700K',
                'base_clock' => 3.8,   // GHz
                'boost_clock' => 5.1,  // GHz
                'cores' => 8,
                'threads' => 16,
            ],
        ]);
    }
}
