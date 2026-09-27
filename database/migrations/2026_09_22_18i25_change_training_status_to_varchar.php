<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        // Badilisha status kuwa VARCHAR
        if ($driver === 'pgsql') {
            // PostgreSQL syntax
            DB::statement("ALTER TABLE trainings ALTER COLUMN status TYPE VARCHAR(30)");
            DB::statement("ALTER TABLE trainings ALTER COLUMN status SET NOT NULL");
            DB::statement("ALTER TABLE trainings ALTER COLUMN status SET DEFAULT 'draft'");

            // Badilisha type kuwa VARCHAR (kama column ipo)
            if (Schema::hasColumn('trainings', 'type')) {
                DB::statement("ALTER TABLE trainings ALTER COLUMN type TYPE VARCHAR(30)");
                DB::statement("ALTER TABLE trainings ALTER COLUMN type SET NOT NULL");
                DB::statement("ALTER TABLE trainings ALTER COLUMN type SET DEFAULT 'internal'");
            }
        } else {
            // MySQL syntax
            DB::statement("ALTER TABLE trainings MODIFY COLUMN status VARCHAR(30) NOT NULL DEFAULT 'draft'");

            if (Schema::hasColumn('trainings', 'type')) {
                DB::statement("ALTER TABLE trainings MODIFY COLUMN type VARCHAR(30) NOT NULL DEFAULT 'internal'");
            }
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            // PostgreSQL — rudisha ENUM (inahitaji custom type)
            // Kwa urahisi, tunarudisha VARCHAR
            DB::statement("ALTER TABLE trainings ALTER COLUMN status TYPE VARCHAR(30)");
            DB::statement("ALTER TABLE trainings ALTER COLUMN status SET DEFAULT 'pending'");
        } else {
            // MySQL
            DB::statement("ALTER TABLE trainings MODIFY COLUMN status ENUM('pending', 'active', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");

            if (Schema::hasColumn('trainings', 'type')) {
                DB::statement("ALTER TABLE trainings MODIFY COLUMN type ENUM('internal', 'external') NOT NULL DEFAULT 'internal'");
            }
        }
    }
};