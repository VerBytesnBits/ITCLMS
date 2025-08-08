<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('system_units', function (Blueprint $table) {
            $table->id();

            // Foreign key to rooms table with cascade delete
            $table->foreignId('room_id')->constrained()->onDelete('cascade');

            // PC name or label
            $table->string('name');

            // serial_number nullable
            $table->string('serial_number')->nullable();

            // brand nullable
            $table->string('brand')->nullable();

            // inventory_code nullable
            $table->string('inventory_code')->nullable();

            // Status enum with default 'Working'
            $table->enum('status', ['Working', 'Under Maintenance', 'Decommissioned'])->default('Working');

            // Date purchased nullable
            $table->date('date_purchased')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_units');
    }
};

