<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Document identification
            if (!Schema::hasColumn('documents', 'document_number')) {
                $table->string('document_number')->nullable()->unique()->after('id');
            }

            // Description
            if (!Schema::hasColumn('documents', 'description')) {
                $table->text('description')->nullable()->after('title');
            }

            // Status & versioning
            if (!Schema::hasColumn('documents', 'status')) {
                $table->string('status')->default('draft')->after('category');
            }
            if (!Schema::hasColumn('documents', 'version')) {
                $table->decimal('version', 5, 2)->default(1.0)->after('status');
            }

            // Visibility
            if (!Schema::hasColumn('documents', 'visibility')) {
                $table->string('visibility')->default('team')->after('version');
            }

            // Dates
            if (!Schema::hasColumn('documents', 'issue_date')) {
                $table->date('issue_date')->nullable();
            }
            if (!Schema::hasColumn('documents', 'expiry_date')) {
                $table->date('expiry_date')->nullable();
            }

            // Relationships
            if (!Schema::hasColumn('documents', 'employee_id')) {
                $table->unsignedBigInteger('employee_id')->nullable();
            }
            if (!Schema::hasColumn('documents', 'project_id')) {
                $table->unsignedBigInteger('project_id')->nullable();
            }

            // Metadata
            if (!Schema::hasColumn('documents', 'tags')) {
                $table->text('tags')->nullable();
            }
            if (!Schema::hasColumn('documents', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('documents', 'file_name')) {
                $table->string('file_name')->nullable();
            }

            // Approval
            if (!Schema::hasColumn('documents', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable();
            }
            if (!Schema::hasColumn('documents', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }

            // Archive
            if (!Schema::hasColumn('documents', 'archived_at')) {
                $table->timestamp('archived_at')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $columns = [
                'document_number', 'description', 'status', 'version',
                'visibility', 'issue_date', 'expiry_date',
                'employee_id', 'project_id', 'tags', 'notes', 'file_name',
                'approved_by', 'approved_at', 'archived_at',
            ];

            foreach ($columns as $col) {
                if (Schema::hasColumn('documents', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};