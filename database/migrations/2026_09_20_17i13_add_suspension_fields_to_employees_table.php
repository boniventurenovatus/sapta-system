<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'suspension_reason')) {
                $table->text('suspension_reason')->nullable()->after('employment_status');
            }
            if (!Schema::hasColumn('employees', 'suspended_at')) {
                $table->timestamp('suspended_at')->nullable()->after('suspension_reason');
            }
            if (!Schema::hasColumn('employees', 'suspended_by')) {
                $table->foreignId('suspended_by')->nullable()->after('suspended_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['suspension_reason', 'suspended_at', 'suspended_by']);
        });
    }
};