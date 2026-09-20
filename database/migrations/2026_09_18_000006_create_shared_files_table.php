<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shared_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->default(0);
            $table->unsignedBigInteger('uploaded_by');
            $table->unsignedBigInteger('conversation_id')->nullable();
            $table->unsignedBigInteger('message_id')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->enum('visibility', ['private', 'team', 'public'])->default('team');
            $table->json('shared_with')->nullable(); // array of user IDs
            $table->integer('download_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['uploaded_by', 'visibility']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shared_files');
    }
};