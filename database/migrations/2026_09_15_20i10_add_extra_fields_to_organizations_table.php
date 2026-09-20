<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->constrained('organizations')->nullOnDelete();
            }
            if (!Schema::hasColumn('organizations', 'type')) {
                $table->enum('type', ['headquarters', 'branch', 'subsidiary'])->default('headquarters');
            }
            if (!Schema::hasColumn('organizations', 'phone')) {
                $table->string('phone', 50)->nullable();
            }
            if (!Schema::hasColumn('organizations', 'email')) {
                $table->string('email', 150)->nullable();
            }
            if (!Schema::hasColumn('organizations', 'website')) {
                $table->string('website', 200)->nullable();
            }
            if (!Schema::hasColumn('organizations', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('organizations', 'city')) {
                $table->string('city', 100)->nullable();
            }
            if (!Schema::hasColumn('organizations', 'region')) {
                $table->string('region', 100)->nullable();
            }
            if (!Schema::hasColumn('organizations', 'country')) {
                $table->string('country', 100)->default('Tanzania');
            }
            if (!Schema::hasColumn('organizations', 'logo')) {
                $table->string('logo', 500)->nullable();
            }
            if (!Schema::hasColumn('organizations', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('organizations', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['parent_id', 'type', 'phone', 'email', 'website', 'address', 'city', 'region', 'country', 'logo', 'is_active', 'notes']);
        });
    }
};
