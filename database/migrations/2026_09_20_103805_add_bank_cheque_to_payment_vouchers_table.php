<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_vouchers', function (Blueprint $table) {
            if (!Schema::hasColumn('payment_vouchers', 'bank')) {
                $table->string('bank', 50)->nullable()->after('mode');
            }
            if (!Schema::hasColumn('payment_vouchers', 'cheque_number')) {
                $table->string('cheque_number', 50)->nullable()->after('bank');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payment_vouchers', function (Blueprint $table) {
            if (Schema::hasColumn('payment_vouchers', 'cheque_number')) {
                $table->dropColumn('cheque_number');
            }
            if (Schema::hasColumn('payment_vouchers', 'bank')) {
                $table->dropColumn('bank');
            }
        });
    }
};