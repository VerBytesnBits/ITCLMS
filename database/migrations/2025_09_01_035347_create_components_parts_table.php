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
        Schema::create('component_parts', function (Blueprint $table) {
            $table->id();
             $table->foreignId('system_unit_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('serial_number')->unique();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('type')->nullable();
            $table->string('capacity')->nullable();
            $table->string('speed')->nullable();
            $table->string('part')->nullable();
            $table->enum('condition', ['Excellent', 'Good', 'Fair', 'Poor'])->default('Good');
            $table->enum('status', ['Available', 'In Use', 'Under Maintenance', 'Defective'])->default('Available');
            $table->date('warranty')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('component_parts');
    }
};
