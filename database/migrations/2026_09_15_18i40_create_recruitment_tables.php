<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('job_number', 50)->unique();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'internship'])->default('full_time');
            $table->enum('experience_level', ['entry', 'mid', 'senior', 'executive'])->default('mid');
            $table->integer('vacancies')->default(1);
            $table->decimal('salary_min', 15, 2)->nullable();
            $table->decimal('salary_max', 15, 2)->nullable();
            $table->string('currency', 10)->default('TZS');
            $table->string('location', 255)->nullable();
            $table->date('posted_date');
            $table->date('closing_date');
            $table->enum('status', ['draft', 'open', 'closed', 'filled', 'cancelled'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('posted_date');
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained('job_postings')->cascadeOnDelete();
            $table->string('applicant_name', 200);
            $table->string('email', 150);
            $table->string('phone', 50)->nullable();
            $table->string('resume_path', 500)->nullable();
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['applied', 'screening', 'interview', 'offered', 'hired', 'rejected'])->default('applied');
            $table->integer('rating')->nullable();
            $table->timestamp('interview_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('job_posting_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_postings');
    }
};
