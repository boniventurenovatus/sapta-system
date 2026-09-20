<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('audience', ['all', 'department', 'role', 'custom'])->default('all');
            $table->json('target_ids')->nullable(); // kwa department/role IDs
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['priority', 'published_at']);
            $table->index('audience');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};