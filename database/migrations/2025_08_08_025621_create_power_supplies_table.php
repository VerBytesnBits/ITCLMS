<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('power_supplies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_unit_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete(); // <- important

            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->integer('wattage')->nullable();
            $table->string('efficiency_rating')->nullable(); // e.g., 80+ Bronze
            $table->boolean('modular')->nullable(); // true/false
            $table->string('serial_number')->nullable();
            $table->enum('status', ['Operational', 'Needs Repair', 'Non-operational'])->default('Operational');
            $table->enum('condition', ['New', 'Excellent', 'Good', 'Fair', 'Poor', 'Defective'])->default('New');
            $table->date('date_purchased')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('power_supplies');
    }
};
