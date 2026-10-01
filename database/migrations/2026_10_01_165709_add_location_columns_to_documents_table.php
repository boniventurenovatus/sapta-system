<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            if (!Schema::hasColumn('documents', 'region_id')) {
                $table->unsignedBigInteger('region_id')->nullable()->after('project_id');
            }
            if (!Schema::hasColumn('documents', 'district_id')) {
                $table->unsignedBigInteger('district_id')->nullable()->after('region_id');
            }
            if (!Schema::hasColumn('documents', 'ward_id')) {
                $table->unsignedBigInteger('ward_id')->nullable()->after('district_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $columns = ['region_id', 'district_id', 'ward_id'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('documents', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};