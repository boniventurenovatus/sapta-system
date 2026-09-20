<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drafts', function (Blueprint $table) {
            $table->id();
            $table->string('draftable_type')->nullable();
            $table->unsignedBigInteger('draftable_id')->nullable();
            $table->string('form_type');
            $table->string('title');
            $table->json('data');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['draft', 'submitted', 'returned', 'approved', 'rejected'])->default('draft');
            $table->text('return_reason')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('returned_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['form_type', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drafts');
    }
};