<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('system_units', function (Blueprint $table) {
            // Component FKs
            $table->foreignId('processor_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('cpu_cooler_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('motherboard_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('memory_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('graphics_card_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('power_supply_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('computer_case_id')->nullable()->constrained()->onDelete('set null');

            // Special drive handling — cannot constrain because it’s polymorphic
            $table->unsignedBigInteger('drive_id')->nullable();
            $table->string('drive_type')->nullable(); // 'm2', 'sata', 'hdd'
        });
    }

    public function down(): void
    {
        Schema::table('system_units', function (Blueprint $table) {
            // Drop FKs first
            $table->dropForeign(['processor_id']);
            $table->dropForeign(['cpu_cooler_id']);
            $table->dropForeign(['motherboard_id']);
            $table->dropForeign(['memory_id']);
            $table->dropForeign(['graphics_card_id']);
            $table->dropForeign(['power_supply_id']);
            $table->dropForeign(['computer_case_id']);

            // Drop columns
            $table->dropColumn([
                'processor_id',
                'cpu_cooler_id',
                'motherboard_id',
                'memory_id',
                'graphics_card_id',
                'power_supply_id',
                'computer_case_id',
                'drive_id',
                'drive_type',
            ]);
        });
    }
};
