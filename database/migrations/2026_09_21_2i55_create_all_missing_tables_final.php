<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SALARIES
        if (!Schema::hasTable('salaries')) {
            Schema::create('salaries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
                $table->decimal('basic_salary', 15, 2)->default(0);
                $table->decimal('allowances', 15, 2)->default(0);
                $table->decimal('deductions', 15, 2)->default(0);
                $table->decimal('net_salary', 15, 2)->default(0);
                $table->string('period')->nullable();
                $table->string('status')->default('active');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // NOTIFICATIONS
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('type');
                $table->morphs('notifiable');
                $table->text('data');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // DOCUMENTS
        if (!Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('category')->nullable();
                $table->string('file_path')->nullable();
                $table->string('file_type', 150)->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // BUDGETS
        if (!Schema::hasTable('budgets')) {
            Schema::create('budgets', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('fiscal_year')->nullable();
                $table->decimal('allocated_amount', 15, 2)->default(0);
                $table->decimal('spent_amount', 15, 2)->default(0);
                $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // ATTENDANCES
        if (!Schema::hasTable('attendances')) {
            Schema::create('attendances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
                $table->date('date')->nullable();
                $table->time('check_in')->nullable();
                $table->time('check_out')->nullable();
                $table->string('status')->default('present');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        // LEAVE REQUESTS
        if (!Schema::hasTable('leave_requests')) {
            Schema::create('leave_requests', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
                $table->string('leave_type')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->integer('total_days')->nullable();
                $table->text('reason')->nullable();
                $table->string('status')->default('pending');
                $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('approved_at')->nullable();
                $table->text('rejection_reason')->nullable();
                $table->timestamps();
            });
        }

        // PAYMENT VOUCHERS
        if (!Schema::hasTable('payment_vouchers')) {
            Schema::create('payment_vouchers', function (Blueprint $table) {
                $table->id();
                $table->string('voucher_number')->unique();
                $table->string('payee')->nullable();
                $table->decimal('amount', 15, 2)->default(0);
                $table->text('description')->nullable();
                $table->string('status')->default('pending');
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // EXPENSE CLAIMS
        if (!Schema::hasTable('expense_claims')) {
            Schema::create('expense_claims', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
                $table->string('title');
                $table->decimal('amount', 15, 2)->default(0);
                $table->string('category')->nullable();
                $table->text('description')->nullable();
                $table->string('status')->default('pending');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // RECEIPTS
        if (!Schema::hasTable('receipts')) {
            Schema::create('receipts', function (Blueprint $table) {
                $table->id();
                $table->string('receipt_number')->unique();
                $table->string('payer')->nullable();
                $table->decimal('amount', 15, 2)->default(0);
                $table->text('description')->nullable();
                $table->date('receipt_date')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // TRAININGS
        if (!Schema::hasTable('trainings')) {
            Schema::create('trainings', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('category')->nullable();
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->string('status')->default('upcoming');
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // PROCUREMENT REQUESTS
        if (!Schema::hasTable('procurement_requests')) {
            Schema::create('procurement_requests', function (Blueprint $table) {
                $table->id();
                $table->string('request_number')->unique();
                $table->string('title');
                $table->text('description')->nullable();
                $table->decimal('estimated_cost', 15, 2)->nullable();
                $table->string('status')->default('pending');
                $table->foreignId('requested_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // ANNOUNCEMENTS
        if (!Schema::hasTable('announcements')) {
            Schema::create('announcements', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('content')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        // SHARED FILES
        if (!Schema::hasTable('shared_files')) {
            Schema::create('shared_files', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('file_path')->nullable();
                $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // CONVERSATIONS
        if (!Schema::hasTable('conversations')) {
            Schema::create('conversations', function (Blueprint $table) {
                $table->id();
                $table->string('title')->nullable();
                $table->timestamps();
            });
        }

        // MESSAGES
        if (!Schema::hasTable('messages')) {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('conversation_id')->nullable()->constrained('conversations')->nullOnDelete();
                $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
                $table->text('body')->nullable();
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
        }

        // GROUPS
        if (!Schema::hasTable('groups')) {
            Schema::create('groups', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // DRAFTS
        if (!Schema::hasTable('drafts')) {
            Schema::create('drafts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('title')->nullable();
                $table->text('content')->nullable();
                $table->timestamps();
            });
        }

        // SUBMISSIONS
        if (!Schema::hasTable('submissions')) {
            Schema::create('submissions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('form_type')->nullable();
                $table->string('title')->nullable();
                $table->json('data')->nullable();
                $table->string('status')->default('pending');
                $table->morphs('submittable');
                $table->timestamps();
            });
        }

        // PERFORMANCE REVIEWS
        if (!Schema::hasTable('performance_reviews')) {
            Schema::create('performance_reviews', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
                $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('period')->nullable();
                $table->integer('score')->nullable();
                $table->text('comments')->nullable();
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }

        // PAYSLIPS — kama haipo
        if (!Schema::hasTable('payslips')) {
            Schema::create('payslips', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
                $table->string('period')->nullable();
                $table->decimal('basic_salary', 15, 2)->default(0);
                $table->decimal('allowances', 15, 2)->default(0);
                $table->decimal('deductions', 15, 2)->default(0);
                $table->decimal('net_salary', 15, 2)->default(0);
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Usifute — kwa usalama
    }
};