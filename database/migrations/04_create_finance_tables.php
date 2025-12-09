<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Setting Gaji Pegawai (Gaji Pokok Individu)
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('position_allowance', 15, 2)->default(0);
            $table->decimal('daily_transport_allowance', 15, 2)->default(0);
            $table->decimal('daily_meal_allowance', 15, 2)->default(0);
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->timestamps();
        });

        // 2. Pinjaman
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->integer('installments')->default(1);
            $table->decimal('installment_amount', 15, 2);
            $table->decimal('remaining_amount', 15, 2);
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'active', 'paid_off', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        // 3. Payroll (Arsip Slip Gaji)
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('month');
            $table->date('generated_date');
            $table->decimal('basic_salary', 15, 2);
            $table->decimal('allowances', 15, 2);
            $table->decimal('overtime_pay', 15, 2);
            $table->decimal('deductions', 15, 2);
            $table->decimal('net_salary', 15, 2);
            $table->json('details')->nullable();
            $table->enum('status', ['draft', 'paid'])->default('draft');
            $table->timestamps();
        });

        // 4. Audit Log
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action');
            $table->string('target_table');
            $table->unsignedBigInteger('target_id');
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('loans');
        Schema::dropIfExists('employee_salaries');
    }
};
