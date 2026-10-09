<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ondoa check constraint ya zamani (kama ipo)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE employees DROP CONSTRAINT IF EXISTS employees_employment_type_check');
            
            // Ongeza check constraint mpya na values zote
            DB::statement("ALTER TABLE employees ADD CONSTRAINT employees_employment_type_check CHECK (employment_type IN ('full_time', 'part_time', 'contract', 'temporary', 'intern', 'internship', 'volunteer', 'consultant', 'probation'))");
        }
        
        // Pia fix contract_type (kama kuna constraint)
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE employees DROP CONSTRAINT IF EXISTS employees_contract_type_check');
            DB::statement("ALTER TABLE employees ADD CONSTRAINT employees_contract_type_check CHECK (contract_type IN ('permanent', 'temporary', 'contract', 'internship', 'probation', 'casual'))");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE employees DROP CONSTRAINT IF EXISTS employees_employment_type_check');
            DB::statement('ALTER TABLE employees DROP CONSTRAINT IF EXISTS employees_contract_type_check');
        }
    }
};