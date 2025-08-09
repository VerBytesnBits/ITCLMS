<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ComputerCase;

class ComputerCaseSeeder extends Seeder
{
    public function run()
    {
        ComputerCase::insert([
            [
                'brand' => 'NZXT',
                'model' => 'H510',
                'form_factor' => 'ATX',
            ],
            [
                'brand' => 'Fractal Design',
                'model' => 'Meshify C',
                'form_factor' => 'ATX',
            ],
        ]);
    }
}
