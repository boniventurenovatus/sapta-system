<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            if (!Schema::hasColumn('payslips', 'verification_token')) {
                $table->string('verification_token', 64)->nullable()->unique()->after('payslip_number');
            }
            if (!Schema::hasColumn('payslips', 'token_expires_at')) {
                $table->timestamp('token_expires_at')->nullable()->after('verification_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payslips', function (Blueprint $table) {
            $table->dropColumn(['verification_token', 'token_expires_at']);
        });
    }
};
