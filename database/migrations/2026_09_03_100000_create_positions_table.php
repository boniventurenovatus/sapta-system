<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organizational_unit_id')
                ->constrained('organizational_units')
                ->restrictOnDelete();

            $table->string('title', 150);
            $table->string('code', 50);

            $table->text('description')->nullable();

            $table->string('status', 30)
                ->default('active');

            $table->timestamps();

            $table->unique([
                'organizational_unit_id',
                'code',
            ]);

            $table->index('title');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
