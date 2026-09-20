<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_vouchers', 'payee_contact')) {
                $table->string('payee_contact')->nullable()->after('payee_type');
            }
            if (!Schema::hasColumn('payment_vouchers', 'payee_account')) {
                $table->string('payee_account')->nullable()->after('payee_contact');
            }
            if (!Schema::hasColumn('payment_vouchers', 'budget_line')) {
                $table->string('budget_line')->nullable()->after('project_id');
            }
            if (!Schema::hasColumn('payment_vouchers', 'return_reason')) {
                $table->text('return_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('payment_vouchers', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('approved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payment_vouchers', function (Blueprint $table) {
            $table->dropColumnIfExists(['payee_contact', 'payee_account', 'budget_line', 'return_reason', 'submitted_at']);
        });
    }
};