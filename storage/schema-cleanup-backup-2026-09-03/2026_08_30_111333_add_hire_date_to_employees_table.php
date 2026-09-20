<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('employees', 'hire_date')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->date('hire_date')->nullable()->after('employment_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('employees', 'hire_date')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('hire_date');
            });
        }
    }
};