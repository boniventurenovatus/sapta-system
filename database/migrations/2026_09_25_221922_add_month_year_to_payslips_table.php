<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            // Ongeza columns zisizopo
            if (!Schema::hasColumn('payslips', 'month')) {
                $table->integer('month')->nullable()->after('salary_id');
            }
            if (!Schema::hasColumn('payslips', 'year')) {
                $table->integer('year')->nullable()->after('month');
            }
            if (!Schema::hasColumn('payslips', 'payslip_number')) {
                $table->string('payslip_number', 50)->nullable()->unique()->after('year');
            }
            if (!Schema::hasColumn('payslips', 'district_id')) {
                $table->unsignedBigInteger('district_id')->nullable()->after('employee_id');
            }
            if (!Schema::hasColumn('payslips', 'ward_id')) {
                $table->unsignedBigInteger('ward_id')->nullable()->after('district_id');
            }
            if (!Schema::hasColumn('payslips', 'total_allowances')) {
                $table->decimal('total_allowances', 15, 2)->default(0)->after('basic_salary');
            }
            if (!Schema::hasColumn('payslips', 'gross_salary')) {
                $table->decimal('gross_salary', 15, 2)->default(0)->after('total_allowances');
            }
            if (!Schema::hasColumn('payslips', 'total_deductions')) {
                $table->decimal('total_deductions', 15, 2)->default(0)->after('gross_salary');
            }
        });

        // Ongeza unique constraint kwa employee_id + month + year
        try {
            Schema::table('payslips', function (Blueprint $table) {
                $table->unique(
                    ['employee_id', 'month', 'year'],
                    'payslips_employee_month_year_unique'
                );
            });
        } catch (\Exception $e) {
            \Log::warning('Unique constraint already exists: ' . $e->getMessage());
        }
    }

    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            try {
                $table->dropUnique('payslips_employee_month_year_unique');
            } catch (\Exception $e) {}

            foreach (['month', 'year', 'payslip_number', 'district_id', 'ward_id',
                      'total_allowances', 'gross_salary', 'total_deductions'] as $col) {
                if (Schema::hasColumn('payslips', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};