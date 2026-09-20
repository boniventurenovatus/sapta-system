<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Personal
            if (!Schema::hasColumn('employees', 'nationality')) {
                $table->string('nationality', 100)->nullable();
            }
            if (!Schema::hasColumn('employees', 'marital_status')) {
                $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable();
            }
            if (!Schema::hasColumn('employees', 'city')) {
                $table->string('city', 100)->nullable();
            }
            if (!Schema::hasColumn('employees', 'region')) {
                $table->string('region', 100)->nullable();
            }
            if (!Schema::hasColumn('employees', 'alternative_phone')) {
                $table->string('alternative_phone', 20)->nullable();
            }

            // Employment
            if (!Schema::hasColumn('employees', 'position_id')) {
                $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            }
            if (!Schema::hasColumn('employees', 'organizational_unit_id')) {
                $table->foreignId('organizational_unit_id')->nullable()->constrained('organizational_units')->nullOnDelete();
            }
            if (!Schema::hasColumn('employees', 'employment_type')) {
                $table->enum('employment_type', ['full_time', 'part_time', 'contract', 'internship'])->default('full_time');
            }
            if (!Schema::hasColumn('employees', 'contract_type')) {
                $table->enum('contract_type', ['permanent', 'temporary', 'probation'])->default('permanent');
            }
            if (!Schema::hasColumn('employees', 'salary')) {
                $table->decimal('salary', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('employees', 'bank_account')) {
                $table->string('bank_account', 100)->nullable();
            }
            if (!Schema::hasColumn('employees', 'bank_name')) {
                $table->string('bank_name', 100)->nullable();
            }
            if (!Schema::hasColumn('employees', 'tin_number')) {
                $table->string('tin_number', 50)->nullable();
            }
            if (!Schema::hasColumn('employees', 'nssf_number')) {
                $table->string('nssf_number', 50)->nullable();
            }
            if (!Schema::hasColumn('employees', 'nhif_number')) {
                $table->string('nhif_number', 50)->nullable();
            }

            // Emergency Contact
            if (!Schema::hasColumn('employees', 'emergency_contact_name')) {
                $table->string('emergency_contact_name', 200)->nullable();
            }
            if (!Schema::hasColumn('employees', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone', 20)->nullable();
            }
            if (!Schema::hasColumn('employees', 'emergency_relationship')) {
                $table->string('emergency_relationship', 100)->nullable();
            }
        });
    }

    public function down(): void
    {
        // Drop columns
    }
};
