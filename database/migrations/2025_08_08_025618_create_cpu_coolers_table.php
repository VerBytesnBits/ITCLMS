<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cpu_coolers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_unit_id')->constrained()->cascadeOnDelete();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('type')->nullable(); // Air, Liquid
            $table->integer('fan_size')->nullable(); // mm
            $table->string('serial_number')->nullable();
            $table->enum('status', ['Working', 'Faulty', 'Under Maintenance'])->default('Working');
            $table->date('date_purchased')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpu_coolers');
    }
};
