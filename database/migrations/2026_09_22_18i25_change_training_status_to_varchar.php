<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Badilisha status kuwa VARCHAR
        DB::statement("ALTER TABLE trainings MODIFY COLUMN status VARCHAR(30) NOT NULL DEFAULT 'draft'");
        
        // Badilisha type kuwa VARCHAR
        DB::statement("ALTER TABLE trainings MODIFY COLUMN type VARCHAR(30) NOT NULL DEFAULT 'internal'");
    }

    public function down(): void
    {
        // Rudisha ENUM
        DB::statement("ALTER TABLE trainings MODIFY COLUMN status ENUM('pending', 'active', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE trainings MODIFY COLUMN type ENUM('internal', 'external') NOT NULL DEFAULT 'internal'");
    }
};