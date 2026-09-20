<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_vouchers', function (Blueprint $table) {
            // Trans No (auto-generated, e.g. PY00001)
            if (!Schema::hasColumn('payment_vouchers', 'trans_no')) {
                $table->string('trans_no', 20)->nullable()->unique()->after('id');
            }

            // Payee P.O Box
            if (!Schema::hasColumn('payment_vouchers', 'payee_pobox')) {
                $table->string('payee_pobox')->nullable()->after('payee_name');
            }

            // Batch (auto-generated, e.g. 186400/3.00)
            if (!Schema::hasColumn('payment_vouchers', 'batch')) {
                $table->string('batch', 50)->nullable()->after('payee_pobox');
            }

            // Mode of payment (transfer, cheque, cash, mobile_money)
            if (!Schema::hasColumn('payment_vouchers', 'mode')) {
                $table->string('mode', 30)->nullable()->after('payment_method');
            }

            // Amount in words (auto-generated)
            if (!Schema::hasColumn('payment_vouchers', 'amount_in_words')) {
                $table->string('amount_in_words', 500)->nullable()->after('amount');
            }

            // Prepared By
            if (!Schema::hasColumn('payment_vouchers', 'prepared_by_id')) {
                $table->foreignId('prepared_by_id')->nullable()->after('created_by')
                    ->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('payment_vouchers', 'prepared_at')) {
                $table->timestamp('prepared_at')->nullable()->after('prepared_by_id');
            }

            // Checked By
            if (!Schema::hasColumn('payment_vouchers', 'checked_by_id')) {
                $table->foreignId('checked_by_id')->nullable()->after('prepared_at')
                    ->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('payment_vouchers', 'checked_at')) {
                $table->timestamp('checked_at')->nullable()->after('checked_by_id');
            }

            // Authorized By
            if (!Schema::hasColumn('payment_vouchers', 'authorized_by_id')) {
                $table->foreignId('authorized_by_id')->nullable()->after('checked_at')
                    ->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('payment_vouchers', 'authorized_at')) {
                $table->timestamp('authorized_at')->nullable()->after('authorized_by_id');
            }

            // Received By
            if (!Schema::hasColumn('payment_vouchers', 'received_by_name')) {
                $table->string('received_by_name')->nullable()->after('authorized_at');
            }
            if (!Schema::hasColumn('payment_vouchers', 'received_signature')) {
                $table->string('received_signature')->nullable()->after('received_by_name');
            }
            if (!Schema::hasColumn('payment_vouchers', 'received_at')) {
                $table->timestamp('received_at')->nullable()->after('received_signature');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payment_vouchers', function (Blueprint $table) {
            $columns = [
                'trans_no', 'payee_pobox', 'batch', 'mode', 'amount_in_words',
                'prepared_by_id', 'prepared_at',
                'checked_by_id', 'checked_at',
                'authorized_by_id', 'authorized_at',
                'received_by_name', 'received_signature', 'received_at',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('payment_vouchers', $column)) {
                    if (str_ends_with($column, '_by_id')) {
                        $table->dropForeign([$column]);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};