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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();

            // Polymorphic relationship
            $table->morphs('maintainable');
            // This will create: maintainable_id, maintainable_type

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // technician
            $table->string('type')->nullable(); // e.g., Repair, Replacement
            $table->text('description')->nullable();
            $table->enum('status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('started_by')->nullable()->constrained('users');
            $table->foreignId('completed_by')->nullable()->constrained('users');

            $table->timestamps();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
