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
        Schema::table('peripherals', function (Blueprint $table) {
            $table->string('screen_size')->nullable()->after('type'); // Monitor
            $table->string('switch_type')->nullable()->after('screen_size'); // Keyboard
            $table->integer('dpi')->nullable()->after('switch_type');        // Mouse
            $table->string('wattage')->nullable()->after('dpi');             // Speaker
            $table->string('resolution')->nullable()->after('wattage');      // Webcam
            $table->string('capacity_va')->nullable()->after('resolution');  // AVR / UPS
        });
    }

    public function down(): void
    {
        Schema::table('peripherals', function (Blueprint $table) {
            $table->dropColumn([
                'screen_size',
                'switch_type',
                'dpi',
                'wattage',
                'resolution',
                'capacity_va',
            ]);
        });
    }

};
