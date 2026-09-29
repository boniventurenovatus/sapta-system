<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            if (!Schema::hasColumn('payslips', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('status');
            }
            if (!Schema::hasColumn('payslips', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
            if (!Schema::hasColumn('payslips', 'generated_by')) {
                $table->unsignedBigInteger('generated_by')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('payslips', 'paid_by')) {
                $table->unsignedBigInteger('paid_by')->nullable()->after('generated_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->dropColumn(['approved_by', 'approved_at', 'generated_by', 'paid_by']);
        });
    }
};
