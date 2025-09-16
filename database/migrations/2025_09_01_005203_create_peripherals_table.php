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
            $table->foreignId('room_id')->nullable()->constrained()->cascadeOnDelete(); // optional link
            $table->string('serial_number')->unique();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('color')->nullable();
            $table->string('type'); // e.g. Monitor, Keyboard, Mouse, Printer
           
            // Lifecycle status
            $table->enum('condition', ['Excellent', 'Good', 'Fair', 'Poor'])->default('Good');
            $table->enum('status', [
                'Available',
                'In Use',
                'Under Maintenance',
                'Defective',
                'Junk',
                'Disposed',
                'Salvaged',
                'Decommission',
                'Archive',

            ])->default('Available');

            // Purchase & warranty
            $table->date('purchase_date')->nullable();
            $table->integer('warranty_period_months')->nullable();   // ex: 12 months
            $table->date('warranty_expires_at')->nullable();         // auto-calculated

            // Retirement / disposal
            $table->string('retirement_action')->nullable(); // decommission, dispose, salvage, archive
            $table->text('retirement_notes')->nullable();
            $table->timestamp('retired_at')->nullable();

            // Laravel built-ins
            $table->softDeletes();  // deleted_at
            $table->timestamps();   // created_at, updated_at
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
