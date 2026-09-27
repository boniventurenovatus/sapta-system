<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            // PostgreSQL — check constraint
            DB::statement("ALTER TABLE payment_vouchers DROP CONSTRAINT IF EXISTS payment_vouchers_status_check");
            DB::statement("ALTER TABLE payment_vouchers ADD CONSTRAINT payment_vouchers_status_check CHECK (status IN ('draft', 'pending_approval', 'checked', 'authorized', 'approved', 'returned', 'paid', 'cancelled', 'completed'))");
        } else {
            // MySQL — ENUM au VARCHAR
            // Badilisha status kuwa VARCHAR kama ni ENUM
            DB::statement("ALTER TABLE payment_vouchers MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'draft'");
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE payment_vouchers DROP CONSTRAINT IF EXISTS payment_vouchers_status_check");
            DB::statement("ALTER TABLE payment_vouchers ADD CONSTRAINT payment_vouchers_status_check CHECK (status IN ('draft', 'pending_approval', 'approved', 'paid', 'cancelled'))");
        }
    }
};