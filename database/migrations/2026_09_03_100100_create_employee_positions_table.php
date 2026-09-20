<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_positions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            $table->foreignId('position_id')
                ->constrained('positions')
                ->restrictOnDelete();

            $table->date('start_date');

            $table->date('end_date')->nullable();

            $table->boolean('is_primary')
                ->default(false);

            $table->string('status', 30)
                ->default('active');

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('employee_id');
            $table->index('position_id');
            $table->index('status');
            $table->index('start_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_positions');
    }
};
