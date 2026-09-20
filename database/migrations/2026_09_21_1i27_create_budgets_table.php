<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};