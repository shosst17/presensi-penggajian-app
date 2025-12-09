<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('office_locations', function (Blueprint $table) {
            // Kita buat kolom MIN dulu, taruh setelah jam pulang (end_time)
            $table->integer('min_overtime_minutes')->default(60)->after('end_time');

            // Baru buat kolom MAX, taruh setelah MIN
            $table->integer('max_overtime_minutes')->default(120)->after('min_overtime_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('office_locations', function (Blueprint $table) {
            $table->dropColumn(['min_overtime_minutes', 'max_overtime_minutes']);
        });
    }
};
