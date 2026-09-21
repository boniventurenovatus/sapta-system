<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'credentials_sent_at')) {
                $table->timestamp('credentials_sent_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'credentials_expires_at')) {
                $table->timestamp('credentials_expires_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'credentials_channel')) {
                $table->string('credentials_channel', 20)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['credentials_sent_at', 'credentials_expires_at', 'credentials_channel']);
        });
    }
};