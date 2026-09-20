<?php
$migration = '<?php

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
';

file_put_contents('database/migrations/2026_09_16_000005_add_status_to_districts_wards.php', $migration);
echo "Migration created\n";
