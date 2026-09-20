<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('suppliers')) {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('contact_person');
                $table->string('email');
                $table->string('phone');
                $table->text('address')->nullable();
                $table->string('category');
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('procurement_requests')) {
            Schema::create('procurement_requests', function (Blueprint $table) {
                $table->id();
                $table->string('request_number')->unique();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('category');
                $table->integer('quantity')->default(1);
                $table->decimal('estimated_cost', 15, 2)->default(0);
                $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
                $table->date('required_date')->nullable();
                $table->unsignedBigInteger('requested_by')->nullable();
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('purchase_orders')) {
            Schema::create('purchase_orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->unsignedBigInteger('supplier_id');
                $table->date('order_date');
                $table->date('delivery_date')->nullable();
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->enum('status', ['pending', 'approved', 'delivered', 'cancelled'])->default('pending');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('procurement_requests');
        Schema::dropIfExists('suppliers');
    }
};