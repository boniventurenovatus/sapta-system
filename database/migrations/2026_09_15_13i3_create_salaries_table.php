<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};