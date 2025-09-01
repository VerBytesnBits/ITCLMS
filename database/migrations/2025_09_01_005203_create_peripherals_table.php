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
        Schema::create('peripherals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_unit_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete(); // optional link
            $table->string('serial_number')->unique();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->string('type'); // e.g. Monitor, Keyboard, Mouse, Printer
            $table->enum('condition', ['Excellent', 'Good', 'Fair', 'Poor'])->default('Good');
            $table->enum('status', ['Available', 'In Use', 'Defective', 'Under Maintenance'])->default('Available');
            $table->date('warranty')->nullable();
            $table->timestamps();
        });

    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peripherals');
    }
};
