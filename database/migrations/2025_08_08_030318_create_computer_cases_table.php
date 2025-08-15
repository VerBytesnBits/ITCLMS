<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('computer_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_unit_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete(); // <- important

            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('form_factor')->nullable();
            $table->string('color')->nullable();
            $table->string('serial_number')->nullable();
            $table->enum('status', ['Operational', 'Needs Repair', 'Non-operational'])->default('Operational');
            $table->enum('condition', ['New', 'Excellent', 'Good', 'Fair', 'Poor', 'Defective'])->default('New');
            $table->date('date_purchased')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('computer_cases');
    }
};
