<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            if (!Schema::hasColumn('trainings', 'type')) {
                $table->string('type', 30)->nullable()->after('title');
            }
            if (!Schema::hasColumn('trainings', 'duration_hours')) {
                $table->integer('duration_hours')->nullable();
            }
            if (!Schema::hasColumn('trainings', 'cost')) {
                $table->decimal('cost', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('trainings', 'region_id')) {
                $table->unsignedBigInteger('region_id')->nullable();
            }
            if (!Schema::hasColumn('trainings', 'district_id')) {
                $table->unsignedBigInteger('district_id')->nullable();
            }
            if (!Schema::hasColumn('trainings', 'ward_id')) {
                $table->unsignedBigInteger('ward_id')->nullable();
            }
            if (!Schema::hasColumn('trainings', 'organization_id')) {
                $table->unsignedBigInteger('organization_id')->nullable();
            }
            if (!Schema::hasColumn('trainings', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable();
            }
            if (!Schema::hasColumn('trainings', 'trainer_name')) {
                $table->string('trainer_name', 255)->nullable();
            }
            if (!Schema::hasColumn('trainings', 'trainer_email')) {
                $table->string('trainer_email', 255)->nullable();
            }
            if (!Schema::hasColumn('trainings', 'max_participants')) {
                $table->integer('max_participants')->nullable();
            }
            if (!Schema::hasColumn('trainings', 'venue')) {
                $table->string('venue', 255)->nullable();
            }
            if (!Schema::hasColumn('trainings', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn([
                'type', 'duration_hours', 'cost',
                'region_id', 'district_id', 'ward_id',
                'organization_id', 'department_id',
                'trainer_name', 'trainer_email',
                'max_participants', 'venue', 'created_by',
            ]);
        });
    }
};