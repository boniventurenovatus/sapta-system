<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('job_postings')) {
            echo "Table job_postings haipo ? skip\n";
            return;
        }

        Schema::table('job_postings', function (Blueprint $table) {
            if (!Schema::hasColumn('job_postings', 'region_id')) {
                $table->foreignId('region_id')->nullable()->after('id')->constrained('regions')->nullOnDelete();
            }
            if (!Schema::hasColumn('job_postings', 'district_id')) {
                $table->foreignId('district_id')->nullable()->after('region_id')->constrained('districts')->nullOnDelete();
            }
            if (!Schema::hasColumn('job_postings', 'ward_id')) {
                $table->foreignId('ward_id')->nullable()->after('district_id')->constrained('wards')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('job_postings')) return;

        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['ward_id']);
            $table->dropColumn(['region_id', 'district_id', 'ward_id']);
        });
    }
};
