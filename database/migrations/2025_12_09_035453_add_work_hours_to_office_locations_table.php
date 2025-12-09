<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('office_locations', function (Blueprint $table) {
            $table->time('start_time')->default('08:00:00')->after('radius_meters'); // Jam Masuk Default
            $table->time('end_time')->default('17:00:00')->after('start_time');     // Jam Pulang Default
        });
    }

    public function down(): void
    {
        Schema::table('office_locations', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
        });
    }
};
