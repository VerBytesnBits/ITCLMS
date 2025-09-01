<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $components = ['cpus', 'motherboards', 'rams', 'drives', 'gpus', 'psus', 'cases'];

        foreach ($components as $component) {
            Schema::create($component, function (Blueprint $table) {
                $table->id();
                $table->foreignId('system_unit_id')->constrained()->cascadeOnDelete();
                $table->string('serial_number')->unique();
                $table->string('brand')->nullable();
                $table->string('model')->nullable();
                $table->string('type')->nullable();
                $table->string('capacity')->nullable();
                $table->string('speed')->nullable();
                $table->enum('condition', ['Excellent','Good','Fair','Poor'])->default('Good');
                $table->enum('status', ['Available','In Use','Under Maintenance','Defective'])->default('Available');
                $table->string('warranty')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        $components = ['cpus', 'motherboards', 'rams', 'drives', 'gpus', 'psus', 'cases'];

        foreach ($components as $component) {
            Schema::dropIfExists($component);
        }
    }
};
