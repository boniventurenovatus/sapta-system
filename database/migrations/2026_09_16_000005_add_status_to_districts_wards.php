<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table("districts", function (Blueprint $table) {
            if (!Schema::hasColumn("districts", "status")) {
                $table->enum("status", ["active", "inactive"])->default("active")->after("code");
            }
        });

        Schema::table("wards", function (Blueprint $table) {
            if (!Schema::hasColumn("wards", "status")) {
                $table->enum("status", ["active", "inactive"])->default("active")->after("code");
            }
        });
    }

    public function down(): void
    {
        Schema::table("districts", function (Blueprint $table) {
            $table->dropColumn("status");
        });

        Schema::table("wards", function (Blueprint $table) {
            $table->dropColumn("status");
        });
    }
};
