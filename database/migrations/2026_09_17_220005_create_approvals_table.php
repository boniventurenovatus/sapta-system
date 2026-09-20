<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submission_id');
            $table->unsignedBigInteger('approver_id');
            $table->enum('action', ['pending', 'approved', 'rejected', 'returned'])->default('pending');
            $table->text('comment')->nullable();
            $table->integer('order')->default(1);
            $table->timestamp('acted_at')->nullable();
            $table->timestamps();

            $table->index('submission_id');
            $table->index(['approver_id', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};