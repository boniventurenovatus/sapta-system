<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payment_voucher_items')) {
            Schema::create('payment_voucher_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('payment_voucher_id')
                    ->constrained('payment_vouchers')
                    ->cascadeOnDelete();
                $table->string('account_invoice_no', 100)->nullable();
                $table->text('details');
                $table->decimal('amount', 15, 2)->default(0);
                $table->integer('sort_order')->default(0);
                $table->timestamps();

                $table->index('payment_voucher_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_voucher_items');
    }
};