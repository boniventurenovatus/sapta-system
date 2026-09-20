<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->id();
            $table->string('training_number', 50)->unique();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('category', ['orientation', 'technical', 'soft_skills', 'compliance', 'leadership', 'safety', 'other'])->default('other');
            $table->string('trainer_name', 200)->nullable();
            $table->enum('trainer_type', ['internal', 'external'])->default('internal');
            $table->string('trainer_contact', 200)->nullable();
            $table->string('location', 255)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('duration_hours')->default(0);
            $table->integer('max_participants')->default(0);
            $table->decimal('cost', 15, 2)->default(0);
            $table->string('currency', 10)->default('TZS');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->enum('status', ['planned', 'ongoing', 'completed', 'cancelled'])->default('planned');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('category');
            $table->index('start_date');
        });

        Schema::create('training_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')->constrained('trainings')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('status', ['enrolled', 'attended', 'completed', 'dropped'])->default('enrolled');
            $table->decimal('score', 5, 2)->nullable();
            $table->string('certificate_number', 100)->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('feedback')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('training_id');
            $table->index('employee_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_enrollments');
        Schema::dropIfExists('trainings');
    }
};
