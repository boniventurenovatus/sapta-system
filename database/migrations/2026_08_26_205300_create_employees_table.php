<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Organization relationships
            |--------------------------------------------------------------------------
            */

            $table->foreignId('organization_id')
                ->nullable()
                ->constrained('organizations')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Legacy department relationship
            |--------------------------------------------------------------------------
            |
            | Kept temporarily for application compatibility.
            | Will be migrated to organizational_units later.
            |
            */

            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            $table->foreignId('organizational_unit_id')
                ->nullable()
                ->constrained('organizational_units')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Employee information
            |--------------------------------------------------------------------------
            */

            $table->string('employee_number', 50)->unique();

            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);

            $table->string('gender', 20)->nullable();
            $table->date('date_of_birth')->nullable();

            $table->string('phone', 30)->nullable();

            $table->string('email', 150)
                ->nullable()
                ->unique();

            /*
            |--------------------------------------------------------------------------
            | Profile
            |--------------------------------------------------------------------------
            */

            $table->string('profile_image')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Employment
            |--------------------------------------------------------------------------
            */

            $table->date('hire_date')->nullable();

            /*
            | Legacy field.
            | Position will become authoritative after migration.
            */

            $table->string('job_title', 150)->nullable();

            $table->string('employment_status', 30)
                ->default('active');

            /*
            |--------------------------------------------------------------------------
            | Additional information
            |--------------------------------------------------------------------------
            */

            $table->text('address')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('organization_id');
            $table->index('department_id');
            $table->index('organizational_unit_id');
            $table->index('employment_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
