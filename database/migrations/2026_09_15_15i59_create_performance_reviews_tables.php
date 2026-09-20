<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_reviews', function (Blueprint $table) {
            $table->id();
            $table->string('review_number', 50)->unique();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('review_period', 50);
            $table->date('review_date');
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('overall_rating', 3, 1)->default(0);
            $table->text('strengths')->nullable();
            $table->text('improvements')->nullable();
            $table->text('goals')->nullable();
            $table->text('comments')->nullable();
            $table->enum('status', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->index('employee_id');
            $table->index('status');
            $table->index('review_date');
        });

        Schema::create('performance_kpis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('performance_review_id')->constrained('performance_reviews')->cascadeOnDelete();
            $table->string('kpi_name', 200);
            $table->text('description')->nullable();
            $table->string('target', 100)->nullable();
            $table->string('achieved', 100)->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->integer('weight')->default(0);
            $table->timestamps();

            $table->index('performance_review_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_kpis');
        Schema::dropIfExists('performance_reviews');
    }
};
