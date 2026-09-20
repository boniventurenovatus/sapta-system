<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class SaptaRealDataSeeder extends Seeder
{
    public function run(): void
    {
        echo "=== KUUNDA PROJECTS 20 ZA SAPTA ===" . PHP_EOL . PHP_EOL;

        $orgId = DB::table('organizations')->where('name', 'SAPTA Technologies Limited')->value('id') ?? 2;

        // Tafuta project managers
        $projectManagers = Employee::whereNotNull('position_id')
            ->whereIn('position_id', [11, 10])
            ->pluck('id')
            ->toArray();

        if (empty($projectManagers)) {
            $projectManagers = Employee::take(5)->pluck('id')->toArray();
        }

        // STATUS: 'planning', 'in_progress', 'on_hold', 'completed', 'cancelled'
        // PRIORITY: 'low', 'medium', 'high', 'critical'
        $projects = [
            ['name' => 'Kilimo Endelevu kwa Vijana',           'code' => 'SAPTA-KEV-2026', 'region' => 2,  'district' => 1, 'budget' => 250000000,  'status' => 'in_progress', 'priority' => 'high',     'progress' => 45],
            ['name' => 'Elimu ya Afya kwa Wanawake',           'code' => 'SAPTA-EAW-2026', 'region' => 3,  'district' => 10, 'budget' => 180000000, 'status' => 'in_progress', 'priority' => 'high',     'progress' => 62],
            ['name' => 'Upatikanaji wa Maji Safi - Singida',    'code' => 'SAPTA-UMS-2026', 'region' => 22, 'district' => 40, 'budget' => 320000000, 'status' => 'in_progress', 'priority' => 'critical', 'progress' => 28],
            ['name' => 'Uwezeshaji wa Wanawake kiuchumi',      'code' => 'SAPTA-UWK-2026', 'region' => 2,  'district' => 2,  'budget' => 150000000, 'status' => 'in_progress', 'priority' => 'high',     'progress' => 71],
            ['name' => 'Mafunzo ya Ujasiriamali kwa Vijana',   'code' => 'SAPTA-MUV-2026', 'region' => 15, 'district' => 25, 'budget' => 120000000, 'status' => 'in_progress', 'priority' => 'medium',   'progress' => 55],
            ['name' => 'Kilimo cha Umwagiliaji - Morogoro',     'code' => 'SAPTA-KCU-2026', 'region' => 14, 'district' => 20, 'budget' => 280000000, 'status' => 'in_progress', 'priority' => 'high',     'progress' => 38],
            ['name' => 'Afya ya Mama na Mtoto - Mwanza',        'code' => 'SAPTA-AMM-2026', 'region' => 20, 'district' => 35, 'budget' => 220000000, 'status' => 'in_progress', 'priority' => 'critical', 'progress' => 48],
            ['name' => 'Elimu ya Msingi - Boresha Ubora',       'code' => 'SAPTA-EMB-2026', 'region' => 5,  'district' => 12, 'budget' => 200000000, 'status' => 'planning',    'priority' => 'high',     'progress' => 5],
            ['name' => 'Mazingira na Uhifadhi wa Misitu',       'code' => 'SAPTA-MUM-2026', 'region' => 21, 'district' => 38, 'budget' => 160000000, 'status' => 'in_progress', 'priority' => 'medium',   'progress' => 33],
            ['name' => 'Ufugaji wa Kisasa - Mbeya',             'code' => 'SAPTA-UKM-2026', 'region' => 13, 'district' => 22, 'budget' => 190000000, 'status' => 'in_progress', 'priority' => 'medium',   'progress' => 60],
            ['name' => 'TEHAMA kwa Shule za Vijijini',          'code' => 'SAPTA-TSV-2026', 'region' => 18, 'district' => 30, 'budget' => 140000000, 'status' => 'planning',    'priority' => 'medium',   'progress' => 10],
            ['name' => 'Ushirikiano wa Jinsia na Haki',         'code' => 'SAPTA-UJH-2026', 'region' => 2,  'district' => 3,  'budget' => 100000000, 'status' => 'in_progress', 'priority' => 'medium',   'progress' => 42],
            ['name' => 'Mikopo Midogo kwa Wajasiriamali',       'code' => 'SAPTA-MMW-2026', 'region' => 6,  'district' => 14, 'budget' => 350000000, 'status' => 'in_progress', 'priority' => 'high',     'progress' => 51],
            ['name' => 'Kupambana na Ukimwi - Vijana',          'code' => 'SAPTA-KUV-2026', 'region' => 19, 'district' => 32, 'budget' => 170000000, 'status' => 'in_progress', 'priority' => 'critical', 'progress' => 66],
            ['name' => 'Lishe Bora kwa Watoto',                 'code' => 'SAPTA-LBW-2026', 'region' => 17, 'district' => 28, 'budget' => 130000000, 'status' => 'planning',    'priority' => 'high',     'progress' => 8],
            ['name' => 'Mafunzo ya Ufundi kwa Vijana',          'code' => 'SAPTA-MUF-2026', 'region' => 4,  'district' => 8,  'budget' => 210000000, 'status' => 'in_progress', 'priority' => 'medium',   'progress' => 37],
            ['name' => 'Hifadhi ya Mazingira - Pwani',          'code' => 'SAPTA-HMP-2026', 'region' => 7,  'district' => 16, 'budget' => 145000000, 'status' => 'in_progress', 'priority' => 'medium',   'progress' => 29],
            ['name' => 'Ufuatiliaji wa Lishe - Shule',          'code' => 'SAPTA-ULS-2026', 'region' => 10, 'district' => 18, 'budget' => 95000000,  'status' => 'planning',    'priority' => 'medium',   'progress' => 3],
            ['name' => 'Kilimo cha Biashara - Ruvuma',          'code' => 'SAPTA-KCB-2026', 'region' => 24, 'district' => 45, 'budget' => 240000000, 'status' => 'in_progress', 'priority' => 'high',     'progress' => 52],
            ['name' => 'Uwezeshaji wa Vijana - Mtwara',         'code' => 'SAPTA-UVM-2026', 'region' => 16, 'district' => 26, 'budget' => 165000000, 'status' => 'in_progress', 'priority' => 'medium',   'progress' => 44],
        ];

        $createdProj = 0;
        foreach ($projects as $p) {
            if (Project::where('code', $p['code'])->exists()) {
                echo "SKIP: {$p['name']}" . PHP_EOL;
                continue;
            }

            Project::create([
                'name'               => $p['name'],
                'code'               => $p['code'],
                'description'        => 'Mradi wa SAPTA: ' . $p['name'],
                'organization_id'    => $orgId,
                'region_id'          => $p['region'],
                'district_id'        => $p['district'],
                'project_manager_id' => $projectManagers[array_rand($projectManagers)],
                'status'             => $p['status'],
                'priority'           => $p['priority'],
                'start_date'         => now()->subMonths(rand(3, 12))->format('Y-m-d'),
                'end_date'           => now()->addMonths(rand(6, 24))->format('Y-m-d'),
                'budget'             => $p['budget'],
                'actual_cost'        => $p['budget'] * ($p['progress'] / 100),
                'progress'           => $p['progress'],
            ]);
            echo "OK: {$p['name']} ({$p['code']})" . PHP_EOL;
            $createdProj++;
        }

        echo PHP_EOL . "=== MUHTASARI ===" . PHP_EOL;
        echo "Projects zilizoundwa: {$createdProj}" . PHP_EOL;
        echo "Jumla projects: " . Project::count() . PHP_EOL;
        echo "Jumla employees: " . Employee::count() . PHP_EOL;
    }
}