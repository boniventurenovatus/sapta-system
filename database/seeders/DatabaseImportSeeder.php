<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseImportSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== DatabaseImportSeeder ===');

        // ============================================================
        // 1. REGIONS
        // ============================================================
        if (Schema::hasTable('regions')) {
            $regions = json_decode('ERROR 1305 (42000) at line 1: FUNCTION sapta_system.JSON_ARRAYAGG does not exist', true);
            
            if ($regions) {
                foreach ($regions as $region) {
                    DB::table('regions')->updateOrInsert(
                        ['id' => $region['id']],
                        [
                            'name' => $region['name'],
                            'code' => $region['code'],
                            'status' => $region['status'] ?? 'active',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
                $this->command->info('✅ Regions (' . count($regions) . ')');
            }
        }

        // ============================================================
        // 2. DISTRICTS
        // ============================================================
        if (Schema::hasTable('districts')) {
            $districts = json_decode('ERROR 1305 (42000) at line 1: FUNCTION sapta_system.JSON_ARRAYAGG does not exist', true);
            
            if ($districts) {
                foreach ($districts as $district) {
                    DB::table('districts')->updateOrInsert(
                        ['id' => $district['id']],
                        [
                            'region_id' => $district['region_id'],
                            'name' => $district['name'],
                            'code' => $district['code'],
                            'status' => $district['status'] ?? 'active',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
                $this->command->info('✅ Districts (' . count($districts) . ')');
            }
        }

        // ============================================================
        // 3. WARDS
        // ============================================================
        if (Schema::hasTable('wards')) {
            $wards = json_decode('ERROR 1305 (42000) at line 1: FUNCTION sapta_system.JSON_ARRAYAGG does not exist', true);
            
            if ($wards) {
                // Chunk kwa 100 ili kuepuka timeout
                $chunks = array_chunk($wards, 100);
                
                foreach ($chunks as $chunk) {
                    foreach ($chunk as $ward) {
                        DB::table('wards')->updateOrInsert(
                            ['id' => $ward['id']],
                            [
                                'district_id' => $ward['district_id'],
                                'name' => $ward['name'],
                                'code' => $ward['code'],
                                'status' => $ward['status'] ?? 'active',
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
                $this->command->info('✅ Wards (' . count($wards) . ')');
            }
        }

        // ============================================================
        // 4. ORGANIZATIONS
        // ============================================================
        if (Schema::hasTable('organizations')) {
            $organizations = json_decode('ERROR 1305 (42000) at line 1: FUNCTION sapta_system.JSON_ARRAYAGG does not exist', true);
            
            if ($organizations) {
                foreach ($organizations as $org) {
                    DB::table('organizations')->updateOrInsert(
                        ['id' => $org['id']],
                        [
                            'name' => $org['name'],
                            'code' => $org['code'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
                $this->command->info('✅ Organizations (' . count($organizations) . ')');
            }
        }

        // ============================================================
        // 5. DEPARTMENTS
        // ============================================================
        if (Schema::hasTable('departments')) {
            $departments = json_decode('ERROR 1305 (42000) at line 1: FUNCTION sapta_system.JSON_ARRAYAGG does not exist', true);
            
            if ($departments) {
                foreach ($departments as $dept) {
                    DB::table('departments')->updateOrInsert(
                        ['id' => $dept['id']],
                        [
                            'name' => $dept['name'],
                            'code' => $dept['code'],
                            'is_active' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
                $this->command->info('✅ Departments (' . count($departments) . ')');
            }
        }

        $this->command->info('✅ DatabaseImportSeeder imekamilika!');
    }
}