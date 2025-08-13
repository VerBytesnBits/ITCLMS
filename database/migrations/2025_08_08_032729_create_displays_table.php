<?php

// database/migrations/2025_08_08_000200_create_displays_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('displays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_unit_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete(); // <- important

            $table->string('brand');
            $table->string('model');
            $table->string('serial_number')->nullable();
            $table->string('resolution')->nullable(); // e.g. 1920x1080
            $table->decimal('size_inches', 5, 2)->nullable(); // e.g. 24.50
            $table->enum('panel_type', ['IPS', 'TN', 'VA', 'OLED'])->nullable();
            $table->enum('status', ['Working', 'Faulty', 'Repaired'])->default('Working');
            $table->date('date_purchased')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('displays');
    }
};
