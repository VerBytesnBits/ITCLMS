<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('issue_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_unit_id')->nullable()->constrained('system_units')->cascadeOnDelete();
            $table->foreignId('component_part_id')->nullable()->constrained('component_parts')->cascadeOnDelete();
            $table->foreignId('peripheral_id')->nullable()->constrained('peripherals')->cascadeOnDelete();
            $table->string('issue_type');
            $table->text('remarks')->nullable();
            $table->foreignId('reported_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['Pending','In Progress','Resolved','Decommissioned','Replacement Needed'])->default('Pending');
            $table->text('resolution_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_reports');
    }
};
