<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        Schema::table('sata_ssds', function (Blueprint $table) {
            $table->string('type')->nullable()->after('model');
            
        });

        Schema::table('m2_ssds', function (Blueprint $table) {
            $table->string('type')->nullable()->after('model');
            
        });
    }

    public function down(): void
    {

        Schema::table('sata_ssds', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('m2_ssds', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};

