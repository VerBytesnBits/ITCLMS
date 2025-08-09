<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop and re-add foreign keys with nullable + cascade
        $tables = [
            
            'computer_cases' => 'computer_cases_system_unit_id_foreign',
            
        ];

        foreach ($tables as $table => $fkName) {
            Schema::table($table, function (Blueprint $table) use ($fkName) {
                $table->dropForeign($fkName);
                $table->unsignedBigInteger('system_unit_id')->nullable()->change();
                $table->foreign('system_unit_id')
                    ->references('id')
                    ->on('system_units')
                    ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        // Revert back to NOT NULL without cascade
        $tables = [
          
            'computer_cases',
            
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeign(['system_unit_id']);
                $table->unsignedBigInteger('system_unit_id')->nullable(false)->change();
                $table->foreign('system_unit_id')
                    ->references('id')
                    ->on('system_units');
            });
        }
    }
};
