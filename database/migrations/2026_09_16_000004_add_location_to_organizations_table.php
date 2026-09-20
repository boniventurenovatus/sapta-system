<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'region_id')) {
                $table->foreignId('region_id')->nullable()->after('city')->constrained('regions')->nullOnDelete();
            }
            if (!Schema::hasColumn('organizations', 'district_id')) {
                $table->foreignId('district_id')->nullable()->after('region_id')->constrained('districts')->nullOnDelete();
            }
            if (!Schema::hasColumn('organizations', 'ward_id')) {
                $table->foreignId('ward_id')->nullable()->after('district_id')->constrained('wards')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['ward_id']);
            $table->dropColumn(['region_id', 'district_id', 'ward_id']);
        });
    }
};
