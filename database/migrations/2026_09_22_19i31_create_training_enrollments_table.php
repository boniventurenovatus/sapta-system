<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('training_enrollments')) {
            Schema::create('training_enrollments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('training_id')->constrained('trainings')->onDelete('cascade');
                $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
                $table->string('status', 30)->default('enrolled');
                $table->decimal('score', 5, 2)->nullable();
                $table->integer('attendance')->nullable();
                $table->boolean('certificate_issued')->default(false);
                $table->date('completed_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();

                $table->unique(['training_id', 'employee_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('training_enrollments');
    }
};