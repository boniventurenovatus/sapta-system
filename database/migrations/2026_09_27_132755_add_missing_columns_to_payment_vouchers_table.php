<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_vouchers', function (Blueprint $table) {
            // ============================================================
            // COLUMNS ZINAZOKOSEKANA
            // ============================================================
            if (!Schema::hasColumn('payment_vouchers', 'trans_no')) {
                $table->string('trans_no', 20)->nullable()->after('voucher_number');
            }
            if (!Schema::hasColumn('payment_vouchers', 'batch')) {
                $table->string('batch', 50)->nullable()->after('trans_no');
            }
            if (!Schema::hasColumn('payment_vouchers', 'payee_pobox')) {
                $table->string('payee_pobox', 255)->nullable()->after('payee_name');
            }
            if (!Schema::hasColumn('payment_vouchers', 'payee_contact')) {
                $table->string('payee_contact', 100)->nullable()->after('payee_pobox');
            }
            if (!Schema::hasColumn('payment_vouchers', 'bank')) {
                $table->string('bank', 50)->nullable();
            }
            if (!Schema::hasColumn('payment_vouchers', 'cheque_number')) {
                $table->string('cheque_number', 50)->nullable();
            }
            if (!Schema::hasColumn('payment_vouchers', 'mode')) {
                $table->string('mode', 30)->nullable();
            }
            if (!Schema::hasColumn('payment_vouchers', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable();
            }
            if (!Schema::hasColumn('payment_vouchers', 'amount_in_words')) {
                $table->text('amount_in_words')->nullable();
            }
            if (!Schema::hasColumn('payment_vouchers', 'prepared_by_id')) {
                $table->unsignedBigInteger('prepared_by_id')->nullable();
            }
            if (!Schema::hasColumn('payment_vouchers', 'prepared_at')) {
                $table->timestamp('prepared_at')->nullable();
            }
            if (!Schema::hasColumn('payment_vouchers', 'checked_by_id')) {
                $table->unsignedBigInteger('checked_by_id')->nullable();
            }
            if (!Schema::hasColumn('payment_vouchers', 'checked_at')) {
                $table->timestamp('checked_at')->nullable();
            }
            if (!Schema::hasColumn('payment_vouchers', 'authorized_by_id')) {
                $table->unsignedBigInteger('authorized_by_id')->nullable();
            }
            if (!Schema::hasColumn('payment_vouchers', 'authorized_at')) {
                $table->timestamp('authorized_at')->nullable();
            }
            if (!Schema::hasColumn('payment_vouchers', 'received_by_name')) {
                $table->string('received_by_name', 255)->nullable();
            }
            if (!Schema::hasColumn('payment_vouchers', 'received_signature')) {
                $table->string('received_signature', 255)->nullable();
            }
            if (!Schema::hasColumn('payment_vouchers', 'received_at')) {
                $table->timestamp('received_at')->nullable();
            }
        });

        // ============================================================
        // COLUMNS ZA PAYMENT_VOUCHER_ITEMS
        // ============================================================
        if (Schema::hasTable('payment_voucher_items')) {
            Schema::table('payment_voucher_items', function (Blueprint $table) {
                if (!Schema::hasColumn('payment_voucher_items', 'account_invoice_no')) {
                    $table->string('account_invoice_no', 100)->nullable();
                }
                if (!Schema::hasColumn('payment_voucher_items', 'details')) {
                    $table->text('details')->nullable();
                }
                if (!Schema::hasColumn('payment_voucher_items', 'amount')) {
                    $table->decimal('amount', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('payment_voucher_items', 'sort_order')) {
                    $table->integer('sort_order')->default(0);
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('payment_vouchers', function (Blueprint $table) {
            $columns = [
                'trans_no', 'batch', 'payee_pobox', 'payee_contact',
                'bank', 'cheque_number', 'mode', 'project_id',
                'amount_in_words', 'prepared_by_id', 'prepared_at',
                'checked_by_id', 'checked_at', 'authorized_by_id',
                'authorized_at', 'received_by_name', 'received_signature', 'received_at',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('payment_vouchers', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};