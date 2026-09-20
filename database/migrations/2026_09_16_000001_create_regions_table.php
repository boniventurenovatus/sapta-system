<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('regions')) {
            Schema::create('regions', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('code', 20)->unique();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();

                $table->index('code');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
