<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Organization relationships
            |--------------------------------------------------------------------------
            */

            $table->unsignedBigInteger('organization_id')
                ->nullable();

            $table->unsignedBigInteger('department_id')
                ->nullable();

            $table->unsignedBigInteger('organizational_unit_id')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Employee information
            |--------------------------------------------------------------------------
            */

            $table->string('employee_number', 50)
                ->unique();

            $table->string('first_name', 100);

            $table->string('middle_name', 100)
                ->nullable();

            $table->string('last_name', 100);

            $table->string('gender', 20)
                ->nullable();

            $table->date('date_of_birth')
                ->nullable();

            $table->string('phone', 30)
                ->nullable();

            $table->string('email', 150)
                ->nullable()
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Profile
            |--------------------------------------------------------------------------
            */

            $table->string('profile_image')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Employment information
            |--------------------------------------------------------------------------
            */

            $table->date('hire_date')
                ->nullable();

            $table->string('job_title', 150)
                ->nullable();

            $table->string('employment_status', 30)
                ->default('active');

            /*
            |--------------------------------------------------------------------------
            | Additional information
            |--------------------------------------------------------------------------
            */

            $table->text('address')
                ->nullable();

            $table->text('notes')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Timestamps & soft deletes
            |--------------------------------------------------------------------------
            */

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};