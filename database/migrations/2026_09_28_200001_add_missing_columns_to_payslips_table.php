<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            if (!Schema::hasColumn('payslips', 'salary_id')) {
                $table->unsignedBigInteger('salary_id')->nullable();
            }
            if (!Schema::hasColumn('payslips', 'payslip_number')) {
                $table->string('payslip_number')->nullable();
            }
            if (!Schema::hasColumn('payslips', 'month')) {
                $table->integer('month')->nullable();
            }
            if (!Schema::hasColumn('payslips', 'year')) {
                $table->integer('year')->nullable();
            }
            if (!Schema::hasColumn('payslips', 'total_allowances')) {
                $table->decimal('total_allowances', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('payslips', 'gross_salary')) {
                $table->decimal('gross_salary', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('payslips', 'total_deductions')) {
                $table->decimal('total_deductions', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('payslips', 'net_salary')) {
                $table->decimal('net_salary', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('payslips', 'allowances_breakdown')) {
                $table->json('allowances_breakdown')->nullable();
            }
            if (!Schema::hasColumn('payslips', 'deductions_breakdown')) {
                $table->json('deductions_breakdown')->nullable();
            }
            if (!Schema::hasColumn('payslips', 'payment_date')) {
                $table->date('payment_date')->nullable();
            }
            if (!Schema::hasColumn('payslips', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->dropColumn([
                'salary_id', 'payslip_number', 'month', 'year',
                'total_allowances', 'gross_salary', 'total_deductions', 'net_salary',
                'allowances_breakdown', 'deductions_breakdown',
                'payment_date', 'notes'
            ]);
        });
    }
};
