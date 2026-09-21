<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrganizationalUnitsAndPositionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== OrganizationalUnitsAndPositionsSeeder ===');

        // ============================================================
        // 1. ORGANIZATIONAL UNITS
        // ============================================================
        if (Schema::hasTable('organizational_units')) {
            $units = [
                ['id' => 1, 'organization_id' => 2, 'name' => 'Board of Directors', 'code' => 'bod'],
                ['id' => 2, 'organization_id' => 2, 'name' => 'Chief Executive Officer', 'code' => 'ceo'],
                ['id' => 3, 'organization_id' => 2, 'name' => 'Administrative and Operations Department', 'code' => 'admin'],
                ['id' => 4, 'organization_id' => 2, 'name' => 'Program and Technical Department', 'code' => 'prog'],
                ['id' => 5, 'organization_id' => 2, 'name' => 'Monitoring, Evaluation, Accountability and Learning Department', 'code' => 'meal'],
                ['id' => 6, 'organization_id' => 2, 'name' => 'Communications & ICT / Digital Innovation Department', 'code' => 'ict'],
                ['id' => 7, 'organization_id' => 2, 'name' => 'Human Resource', 'code' => 'hr'],
            ];

            foreach ($units as $unit) {
                DB::table('organizational_units')->updateOrInsert(
                    ['id' => $unit['id']],
                    [
                        'organization_id' => $unit['organization_id'],
                        'name' => $unit['name'],
                        'code' => $unit['code'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            $this->command->info('✅ Organizational Units (' . count($units) . ')');
        }

        // ============================================================
        // 2. POSITIONS
        // ============================================================
        if (Schema::hasTable('positions')) {
            $positions = [
                ['id' => 3, 'title' => 'Board of Directors', 'code' => 'bod'],
                ['id' => 4, 'title' => 'Chief Executive Officer (CEO)', 'code' => 'ceo'],
                ['id' => 5, 'title' => 'Administrative Director', 'code' => 'admin_director'],
                ['id' => 6, 'title' => 'Human Resource Management & Administration Manager', 'code' => 'hr_manager'],
                ['id' => 7, 'title' => 'Procurement & Logistics Manager', 'code' => 'procurement_manager'],
                ['id' => 8, 'title' => 'Finance Manager', 'code' => 'finance_manager'],
                ['id' => 9, 'title' => 'Accountant', 'code' => 'accountant'],
                ['id' => 10, 'title' => 'Program & Technical Director', 'code' => 'program_director'],
                ['id' => 11, 'title' => 'Project Manager', 'code' => 'project_manager'],
                ['id' => 12, 'title' => 'Project Officers', 'code' => 'project_officer'],
                ['id' => 13, 'title' => 'Field Trainer', 'code' => 'field_trainer'],
                ['id' => 14, 'title' => 'Partnerships and Resource Mobilization Manager', 'code' => 'partnerships_manager'],
                ['id' => 15, 'title' => 'MEAL Manager', 'code' => 'meal_manager'],
                ['id' => 16, 'title' => 'MEAL Officer', 'code' => 'meal_officer'],
                ['id' => 17, 'title' => 'Research and Innovation Officer', 'code' => 'research_officer'],
                ['id' => 18, 'title' => 'Community Knowledge Manager', 'code' => 'community_manager'],
                ['id' => 19, 'title' => 'ICT & Digital Innovation Manager', 'code' => 'ict_manager'],
            ];

            foreach ($positions as $position) {
                DB::table('positions')->updateOrInsert(
                    ['id' => $position['id']],
                    [
                        'title' => $position['title'],
                        'code' => $position['code'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            $this->command->info('✅ Positions (' . count($positions) . ')');
        }

        $this->command->info('✅ OrganizationalUnitsAndPositionsSeeder imekamilika!');
    }
}