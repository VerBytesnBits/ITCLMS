<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('keyboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_unit_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete(); // <- important

            $table->string('brand');
            $table->string('model');
            $table->string('serial_number')->nullable();
            $table->enum('connection_type', ['Wired', 'Wireless'])->nullable();
            $table->enum('status', ['Working', 'Faulty', 'Repaired'])->default('Working');
            $table->date('date_purchased')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keyboards');
    }
};

