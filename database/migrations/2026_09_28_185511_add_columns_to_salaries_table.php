<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            if (!Schema::hasColumn('salaries', 'house_allowance')) {
                $table->decimal('house_allowance', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('salaries', 'transport_allowance')) {
                $table->decimal('transport_allowance', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('salaries', 'medical_allowance')) {
                $table->decimal('medical_allowance', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('salaries', 'other_allowances')) {
                $table->decimal('other_allowances', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('salaries', 'tax_deduction')) {
                $table->decimal('tax_deduction', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('salaries', 'nssf_deduction')) {
                $table->decimal('nssf_deduction', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('salaries', 'nhif_deduction')) {
                $table->decimal('nhif_deduction', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('salaries', 'loan_deduction')) {
                $table->decimal('loan_deduction', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('salaries', 'other_deductions')) {
                $table->decimal('other_deductions', 15, 2)->default(0);
            }
            if (!Schema::hasColumn('salaries', 'bank_name')) {
                $table->string('bank_name')->nullable();
            }
            if (!Schema::hasColumn('salaries', 'bank_account')) {
                $table->string('bank_account')->nullable();
            }
            if (!Schema::hasColumn('salaries', 'effective_date')) {
                $table->date('effective_date')->nullable();
            }
            if (!Schema::hasColumn('salaries', 'currency')) {
                $table->string('currency', 10)->default('TZS');
            }
            if (!Schema::hasColumn('salaries', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (!Schema::hasColumn('salaries', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            $table->dropColumn([
                'house_allowance', 'transport_allowance', 'medical_allowance', 'other_allowances',
                'tax_deduction', 'nssf_deduction', 'nhif_deduction', 'loan_deduction', 'other_deductions',
                'bank_name', 'bank_account', 'effective_date', 'currency', 'payment_method', 'notes'
            ]);
        });
    }
};
