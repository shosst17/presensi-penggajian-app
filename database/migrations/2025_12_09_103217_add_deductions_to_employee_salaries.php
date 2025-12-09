<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            // Menambah kolom BPJS dan Tax (PPH21) dengan default 0
            $table->decimal('bpjs', 15, 2)->default(0)->after('daily_meal_allowance');
            $table->decimal('tax', 15, 2)->default(0)->after('bpjs');
        });
    }

    public function down(): void
    {
        Schema::table('employee_salaries', function (Blueprint $table) {
            $table->dropColumn(['bpjs', 'tax']);
        });
    }
};
