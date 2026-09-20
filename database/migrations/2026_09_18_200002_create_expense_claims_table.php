<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expense_claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_number')->unique();
            $table->unsignedBigInteger('employee_id');
            $table->string('title');
            $table->string('category')->default('other');
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('TZS');
            $table->date('expense_date');
            $table->text('description');
            $table->string('project')->nullable();
            $table->string('department')->nullable();
            $table->enum('payment_method', ['cash', 'bank_transfer', 'mobile_money', 'cheque'])->default('cash');
            $table->string('receipt_number')->nullable();
            $table->enum('status', ['draft', 'submitted', 'pending_approval', 'approved', 'rejected', 'returned', 'paid', 'completed'])->default('draft');
            $table->text('return_reason')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_by']);
            $table->index('claim_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expense_claims');
    }
};