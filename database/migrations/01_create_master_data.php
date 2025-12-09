<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Lokasi Kantor
        Schema::create('office_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->integer('radius_meters')->default(50);
            $table->timestamps();
        });

        // 2. Departemen
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 3. Jabatan
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            // Tambah baris ini:
            $table->foreignId('department_id')->constrained()->onDelete('cascade');

            $table->string('name'); // Contoh: Senior Programmer, Recruiter
            $table->decimal('base_salary_default', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('office_locations');
    }
};
