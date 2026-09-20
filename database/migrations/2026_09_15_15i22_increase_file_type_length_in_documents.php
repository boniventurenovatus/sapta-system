<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Check kama column ipo
        if (Schema::hasColumn('documents', 'file_type')) {
            // Kwa PostgreSQL, tumia raw SQL
            if (DB::getDriverName() === 'pgsql') {
                DB::statement('ALTER TABLE documents ALTER COLUMN file_type TYPE VARCHAR(150)');
            } else {
                // Kwa SQLite au MySQL, tumia change
                Schema::table('documents', function (Blueprint $table) {
                    $table->string('file_type', 150)->nullable()->change();
                });
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('documents', 'file_type')) {
            if (DB::getDriverName() === 'pgsql') {
                DB::statement('ALTER TABLE documents ALTER COLUMN file_type TYPE VARCHAR(50)');
            } else {
                Schema::table('documents', function (Blueprint $table) {
                    $table->string('file_type', 50)->nullable()->change();
                });
            }
        }
    }
};