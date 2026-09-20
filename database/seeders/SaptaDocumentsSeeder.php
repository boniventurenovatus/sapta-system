<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class SaptaDocumentsSeeder extends Seeder
{
    public function run(): void
    {
        echo "=== KUUNDA DOCUMENTS ZA SAPTA ===" . PHP_EOL . PHP_EOL;

        $adminId = DB::table('users')->where('username', 'superadmin')->value('id') ?? 3;

        // CATEGORY: 'contract', 'policy', 'report', 'invoice', 'receipt', 'certificate', 'memo', 'other'
        $categories = ['contract', 'policy', 'report', 'invoice', 'receipt', 'certificate', 'memo', 'other'];

        // STATUS: 'draft', 'active', 'archived', 'expired'
        $statuses = ['draft', 'active', 'archived', 'expired'];

        // VISIBILITY: 'private', 'team', 'public'
        $visibilities = ['private', 'team', 'public'];

        $docTitles = [
            'Mkataba wa Mradi', 'Ripoti ya Mwezi', 'Ripoti ya Robo Mwaka',
            'Ripoti ya Mwaka', 'Pendekezo la Mradi', 'Sera ya Mradi',
            'Mkakati wa Utekelezaji', 'Barua ya Makubaliano', 'Ripoti ya Tathmini',
            'Ripoti ya Ufuatiliaji', 'Mpango wa Mafunzo', 'Mwongozo wa Mafunzo',
            'Ripoti ya Ukaguzi', 'Hati ya Ushirikiano', 'Ripoti ya Fedha',
            'Bajeti ya Mradi', 'Mkataba wa Washirika', 'Barua ya Idhini',
            'Ripoti ya Semina', 'Hati ya Mafunzo',
        ];

        $projects = Project::all();
        $employees = Employee::pluck('id')->toArray();
        $created = 0;
        $year = date('Y');

        foreach ($projects as $project) {
            $docCount = rand(2, 3);

            for ($i = 0; $i < $docCount; $i++) {
                $docNumber = 'DOC-' . $year . '-' . str_pad((\DB::table('documents')->max('id') + 1), 4, '0', STR_PAD_LEFT);

                if (\DB::table('documents')->where('document_number', $docNumber)->exists()) {
                    continue;
                }

                $title = $docTitles[array_rand($docTitles)];
                $category = $categories[array_rand($categories)];
                $status = $statuses[array_rand($statuses)];
                $visibility = $visibilities[array_rand($visibilities)];

                // TAGS kama JSON array HALALI
                $tags = json_encode([$category, $project->code ?? 'SAPTA', $year]);

                \DB::table('documents')->insert([
                    'document_number' => $docNumber,
                    'title'           => $title . ' - ' . $project->name,
                    'description'     => 'Hati ya ' . $project->name,
                    'category'        => $category,
                    'file_path'       => 'documents/' . $year . '/' . date('m') . '/sample-' . rand(1000, 9999) . '.pdf',
                    'file_name'       => strtolower(str_replace(' ', '-', $title)) . '.pdf',
                    'file_type'       => 'application/pdf',
                    'file_size'       => rand(50000, 5000000),
                    'employee_id'     => $employees[array_rand($employees)] ?? null,
                    'project_id'      => $project->id,
                    'department_id'   => $project->department_id ?? rand(1, 7),
                    'region_id'       => $project->region_id,
                    'district_id'     => $project->district_id,
                    'ward_id'         => rand(1, 900),
                    'status'          => $status,
                    'visibility'      => $visibility,
                    'issue_date'      => now()->subDays(rand(1, 365))->format('Y-m-d'),
                    'expiry_date'     => now()->addDays(rand(30, 730))->format('Y-m-d'),
                    'uploaded_by'     => $adminId,
                    'approved_by'     => $status === 'active' ? $adminId : null,
                    'approved_at'     => $status === 'active' ? now() : null,
                    'version'         => '1.0',
                    'tags'            => $tags,
                    'notes'           => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
                $created++;
            }
        }

        echo "✅ Documents zilizoundwa: {$created}" . PHP_EOL;
        echo "   Jumla documents: " . \DB::table('documents')->count() . PHP_EOL;
    }
}