<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submission_id');
            $table->integer('version_number');
            $table->json('data');
            $table->unsignedBigInteger('created_by');
            $table->text('change_notes')->nullable();
            $table->string('action');
            $table->timestamps();

            $table->index(['submission_id', 'version_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_versions');
    }
};