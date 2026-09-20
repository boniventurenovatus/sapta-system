<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('procurement_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('procurement_requests', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('requested_by');
            }
            if (!Schema::hasColumn('procurement_requests', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
            if (!Schema::hasColumn('procurement_requests', 'return_reason')) {
                $table->text('return_reason')->nullable()->after('status');
            }
            if (!Schema::hasColumn('procurement_requests', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('return_reason');
            }
            if (!Schema::hasColumn('procurement_requests', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        //
    }
};