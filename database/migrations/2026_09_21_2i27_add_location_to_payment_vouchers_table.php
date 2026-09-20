<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payment_vouchers')) {
            Schema::table('payment_vouchers', function (Blueprint $table) {
                if (!Schema::hasColumn('payment_vouchers', 'region_id')) {
                    $table->unsignedBigInteger('region_id')->nullable();
                }
                if (!Schema::hasColumn('payment_vouchers', 'district_id')) {
                    $table->unsignedBigInteger('district_id')->nullable();
                }
                if (!Schema::hasColumn('payment_vouchers', 'ward_id')) {
                    $table->unsignedBigInteger('ward_id')->nullable();
                }
                if (!Schema::hasColumn('payment_vouchers', 'organization_id')) {
                    $table->unsignedBigInteger('organization_id')->nullable();
                }
                if (!Schema::hasColumn('payment_vouchers', 'department_id')) {
                    $table->unsignedBigInteger('department_id')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payment_vouchers')) {
            Schema::table('payment_vouchers', function (Blueprint $table) {
                $table->dropColumn(['region_id', 'district_id', 'ward_id', 'organization_id', 'department_id']);
            });
        }
    }
};