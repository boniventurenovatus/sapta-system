<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class SaptaTasksSeeder extends Seeder
{
    public function run(): void
    {
        echo "=== KUUNDA TASKS ZA SAPTA ===" . PHP_EOL . PHP_EOL;

        $adminId = DB::table('users')->where('username', 'superadmin')->value('id') ?? 3;

        $employees = Employee::whereNotNull('id')->pluck('id')->toArray();

        $taskTitles = [
            'Kutembelea wilaya na kukusanya data',
            'Kuandaa ripoti ya mwezi',
            'Kufanya mafunzo kwa wakulima',
            'Kutafuta washirika wapya',
            'Kuandaa bajeti ya robo mwaka',
            'Kufanya tathmini ya mradi',
            'Kutembelea shule na kufanya usajili',
            'Kusambaza vifaa vya mafunzo',
            'Kufanya mkutano na viongozi wa vijiji',
            'Kuandaa waraka wa mafunzo',
            'Kutengeneza video ya uhamasishaji',
            'Kufanya usajili wa walengwa',
            'Kukusanya data ya awali',
            'Kuandaa semina ya wadau',
            'Kufanya ukaguzi wa mradi',
            'Kuandaa ripoti ya tathmini',
            'Kutengeneza mabango ya elimu',
            'Kufanya usaili wa wafanyakazi',
            'Kuandaa mpango wa mwaka',
            'Kutembelea miradi shirikishi',
            'Kufanya mikutano ya jamii',
            'Kusambaza mbegu bora',
            'Kufanya mafunzo ya ujasiriamali',
            'Kutengeneza mwongozo wa mafunzo',
            'Kufanya uchunguzi wa soko',
        ];

        // ENUM SAHIHI: 'todo', 'in_progress', 'review', 'done'
        $statuses = ['todo', 'in_progress', 'review', 'done'];
        $priorities = ['low', 'medium', 'high', 'critical'];

        $projects = Project::all();
        $created = 0;

        foreach ($projects as $project) {
            $taskCount = rand(3, 5);

            for ($i = 0; $i < $taskCount; $i++) {
                $status = $statuses[array_rand($statuses)];
                $priority = $priorities[array_rand($priorities)];
                $title = $taskTitles[array_rand($taskTitles)];

                $progress = match ($status) {
                    'todo' => 0,
                    'in_progress' => rand(10, 70),
                    'review' => rand(70, 95),
                    'done' => 100,
                    default => 0,
                };

                $startDate = now()->subDays(rand(1, 60));
                $dueDate = now()->addDays(rand(7, 90));

                \DB::table('tasks')->insert([
                    'title'         => $title,
                    'description'   => 'Kazi ya ' . $project->name . ': ' . $title,
                    'project_id'    => $project->id,
                    'region_id'     => $project->region_id,
                    'district_id'   => $project->district_id,
                    'ward_id'       => rand(1, 900),
                    'assigned_to'   => $employees[array_rand($employees)],
                    'created_by'    => $adminId,
                    'status'        => $status,
                    'priority'      => $priority,
                    'start_date'    => $startDate->format('Y-m-d'),
                    'due_date'      => $dueDate->format('Y-m-d'),
                    'completed_date'=> $status === 'done' ? now()->format('Y-m-d') : null,
                    'estimated_hours' => rand(4, 40),
                    'actual_hours'  => $status === 'done' ? rand(4, 40) : null,
                    'progress'      => $progress,
                    'notes'         => null,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
                $created++;
            }
        }

        echo "✅ Tasks zilizoundwa: {$created}" . PHP_EOL;
        echo "   Jumla tasks: " . \DB::table('tasks')->count() . PHP_EOL;
    }
}