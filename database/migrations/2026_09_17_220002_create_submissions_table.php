<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('submission_number')->unique();
            $table->string('submittable_type')->nullable();
            $table->unsignedBigInteger('submittable_id')->nullable();
            $table->string('form_type');
            $table->string('title');
            $table->json('data');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['submitted', 'pending_approval', 'approved', 'rejected', 'returned', 'completed'])->default('submitted');
            $table->text('return_reason')->nullable();
            $table->integer('current_version')->default(1);
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['form_type', 'status']);
            $table->index('submission_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};