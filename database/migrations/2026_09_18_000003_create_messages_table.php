<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id')->nullable();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('recipient_id')->nullable(); // kwa direct messages
            $table->string('subject')->nullable();
            $table->text('body');
            $table->enum('status', ['draft', 'sent', 'delivered', 'read', 'deleted'])->default('sent');
            $table->boolean('is_draft')->default(false);
            $table->boolean('deleted_for_sender')->default(false);
            $table->boolean('deleted_for_recipient')->default(false);
            $table->json('attachments')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['recipient_id', 'is_draft', 'status']);
            $table->index(['sender_id', 'is_draft', 'status']);
            $table->index(['conversation_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};