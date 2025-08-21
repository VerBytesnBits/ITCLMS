<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('unit_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_unit_id')->constrained()->onDelete('cascade');
            $table->string('part_type')->nullable(); // e.g. "Processor", "Memory", "Mouse"
            $table->unsignedBigInteger('part_id')->nullable(); // ID from that part’s table
            $table->foreignId('reported_by')->constrained('users');
            $table->text('issue')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['Operational', 'Needs Repair', 'Non-Operational'])->default('Needs Repair');
            $table->enum('approval_status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('unit_reports');
    }
};

