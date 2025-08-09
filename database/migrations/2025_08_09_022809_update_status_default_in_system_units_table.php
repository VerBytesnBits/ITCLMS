<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_units', function (Blueprint $table) {
            $table->enum('status', ['Operational', 'Needs Repair', 'Non-operational'])
                  ->default('Operational')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('system_units', function (Blueprint $table) {
            // Revert to previous type if needed, example:
            $table->string('status')->change();
        });
    }
};
