<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique();
            $table->string('payee_name');
            $table->string('payee_type')->default('individual');
            $table->string('payee_account')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('TZS');
            $table->string('payment_method')->default('bank_transfer');
            $table->string('project')->nullable();
            $table->string('budget_line')->nullable();
            $table->string('department')->nullable();
            $table->date('payment_date');
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
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
            $table->index('voucher_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_vouchers');
    }
};