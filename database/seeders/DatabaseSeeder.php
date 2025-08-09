<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
    //    $this->call([
    //     RolePermissionSeeder::class,
    //    ]);
    // }
    public function run(): void
{
    $this->call([
        CpuCoolerSeeder::class,
        MotherboardSeeder::class,
        MemorySeeder::class,
        GraphicsCardSeeder::class,
        M2SsdSeeder::class,
        SataSsdSeeder::class,
        HardDiskDriveSeeder::class,
        PowerSupplySeeder::class,
        ComputerCaseSeeder::class,
        ProcessorSeeder::class,
        RolePermissionSeeder::class,
    ]);
}

}
