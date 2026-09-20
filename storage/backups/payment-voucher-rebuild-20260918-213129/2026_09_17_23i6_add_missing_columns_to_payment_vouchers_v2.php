<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_vouchers', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable()->after('payee_contact');
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
            if (!Schema::hasColumn('payment_vouchers', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('submitted_at');
            }
            if (!Schema::hasColumn('payment_vouchers', 'attachments')) {
                $table->json('attachments')->nullable()->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payment_vouchers', function (Blueprint $table) {
            $columns = ['project_id', 'budget_line', 'return_reason', 'submitted_at', 'paid_at', 'attachments'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('payment_vouchers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};