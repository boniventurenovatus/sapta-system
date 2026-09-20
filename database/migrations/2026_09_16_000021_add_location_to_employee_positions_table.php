<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('employee_positions')) {
            echo "Table employee_positions haipo ? skip\n";
            return;
        }

        Schema::table('employee_positions', function (Blueprint $table) {
            if (!Schema::hasColumn('employee_positions', 'region_id')) {
                $table->foreignId('region_id')->nullable()->after('id')->constrained('regions')->nullOnDelete();
            }
            if (!Schema::hasColumn('employee_positions', 'district_id')) {
                $table->foreignId('district_id')->nullable()->after('region_id')->constrained('districts')->nullOnDelete();
            }
            if (!Schema::hasColumn('employee_positions', 'ward_id')) {
                $table->foreignId('ward_id')->nullable()->after('district_id')->constrained('wards')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('employee_positions')) return;

        Schema::table('employee_positions', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['ward_id']);
            $table->dropColumn(['region_id', 'district_id', 'ward_id']);
        });
    }
};
