<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        Schema::create('system_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('serial_number')->unique()->nullable();
            $table->enum('status', ['Operational','Non-operational','Needs Repair'])->default('Operational');
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_units');
    }
};
