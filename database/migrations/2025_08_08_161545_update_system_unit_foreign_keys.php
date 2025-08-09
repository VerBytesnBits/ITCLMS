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
        
            'cpu_coolers' => 'cpu_coolers_system_unit_id_foreign',
            'displays' => 'displays_system_unit_id_foreign',
            'graphics_cards' => 'graphics_cards_system_unit_id_foreign',
            'hard_disk_drives' => 'hard_disk_drives_system_unit_id_foreign',
            'headsets' => 'headsets_system_unit_id_foreign',
            'keyboards' => 'keyboards_system_unit_id_foreign',
            'm2_ssds' => 'm2_ssds_system_unit_id_foreign',
            'memories' => 'memories_system_unit_id_foreign',
            'mice' => 'mice_system_unit_id_foreign',
            'motherboards' => 'motherboards_system_unit_id_foreign',
            'power_supplies' => 'power_supplies_system_unit_id_foreign',
            'processors' => 'processors_system_unit_id_foreign',
            'sata_ssds' => 'sata_ssds_system_unit_id_foreign',
            'speakers' => 'speakers_system_unit_id_foreign',
            'web_digital_cameras' => 'web_digital_cameras_system_unit_id_foreign',
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
          
            'cpu_coolers',
            'displays',
            'graphics_cards',
            'hard_disk_drives',
            'headsets',
            'keyboards',
            'm2_ssds',
            'memories',
            'mice',
            'motherboards',
            'power_supplies',
            'processors',
            'sata_ssds',
            'speakers',
            'web_digital_cameras',
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
