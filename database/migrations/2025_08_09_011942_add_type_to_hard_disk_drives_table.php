<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hard_disk_drives', function (Blueprint $table) {
            $table->string('type')->default('HDD')->after('capacity');
        });
    }

    public function down(): void
    {
        Schema::table('hard_disk_drives', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
