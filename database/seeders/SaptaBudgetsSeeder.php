<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use Illuminate\Support\Facades\DB;

class SaptaBudgetsSeeder extends Seeder
{
    public function run(): void
    {
        echo "=== KUUNDA BUDGETS ZA SAPTA ===" . PHP_EOL . PHP_EOL;

        // Tafuta admin user (kwa created_by)
        $adminId = DB::table('users')->where('username', 'superadmin')->value('id') ?? 3;

        // Categories: 'travel', 'equipment', 'supplies', 'training', 'salary', 'operations', 'other'
        $categories = ['travel', 'equipment', 'supplies', 'training', 'operations'];

        $projects = Project::all();
        $createdBud = 0;
        $year = date('Y');

        foreach ($projects as $project) {
            // Kila project ina budgets 2
            $budgetCount = rand(1, 2);

            for ($i = 0; $i < $budgetCount; $i++) {
                $budgetNumber = 'BGT-' . $year . '-' . str_pad((\DB::table('budgets')->max('id') + 1), 4, '0', STR_PAD_LEFT);

                // Angalia kama budget number ipo
                if (\DB::table('budgets')->where('budget_number', $budgetNumber)->exists()) {
                    continue;
                }

                $category = $categories[array_rand($categories)];
                $allocatedAmount = $project->budget * (rand(20, 50) / 100); // 20-50% ya project budget
                $spentAmount = $allocatedAmount * (rand(0, 70) / 100); // 0-70% spent
                $deptId = $project->department_id ?? rand(1, 7);

                \DB::table('budgets')->insert([
                    'budget_number'   => $budgetNumber,
                    'name'            => $project->name . ' - ' . ucfirst($category) . ' Budget',
                    'fiscal_year'     => $year,
                    'project_id'      => $project->id,
                    'department_id'   => $deptId,
                    'region_id'       => $project->region_id,
                    'district_id'     => $project->district_id,
                    'ward_id'         => rand(1, 900),
                    'category'        => $category,
                    'allocated_amount'=> $allocatedAmount,
                    'spent_amount'    => $spentAmount,
                    'currency'        => 'TZS',
                    'start_date'      => $year . '-01-01',
                    'end_date'        => $year . '-12-31',
                    'status'          => 'approved',
                    'created_by'      => $adminId,
                    'approved_by'     => $adminId,
                    'approved_at'     => now(),
                    'notes'           => 'Budget ya ' . $project->name,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
                $createdBud++;
            }
        }

        echo "✅ Budgets zilizoundwa: {$createdBud}" . PHP_EOL;
        echo "   Jumla budgets: " . \DB::table('budgets')->count() . PHP_EOL;
    }
}