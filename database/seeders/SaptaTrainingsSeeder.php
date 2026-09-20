<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;

class SaptaTrainingsSeeder extends Seeder
{
    public function run(): void
    {
        echo "=== KUUNDA TRAININGS 25 ZA SAPTA ===" . PHP_EOL . PHP_EOL;

        $adminId = DB::table('users')->where('username', 'superadmin')->value('id') ?? 3;

        // CATEGORY: 'soft_skills', 'technical', 'leadership', 'compliance', 'safety', 'other'
        $categories = ['soft_skills', 'technical', 'leadership', 'compliance', 'safety', 'other'];

        // STATUS: 'planned', 'ongoing', 'completed', 'cancelled'
        $statuses = ['planned', 'ongoing', 'completed', 'cancelled'];

        $trainingTitles = [
            'Mafunzo ya Uongozi Bora',
            'Mafunzo ya Usimamizi wa Miradi',
            'Mafunzo ya TEHAMA',
            'Mafunzo ya Uandishi wa Ripoti',
            'Mafunzo ya Mawasiliano',
            'Mafunzo ya Usalama Kazini',
            'Mafunzo ya Fedha kwa Wasio Fedha',
            'Mafunzo ya Ukaguzi wa Ndani',
            'Mafunzo ya Usimamizi wa Rasilimali Watu',
            'Mafunzo ya Ufuatiliaji na Tathmini',
            'Mafunzo ya Ununuzi',
            'Mafunzo ya Kilimo Bora',
            'Mafunzo ya Afya na Usalama',
            'Mafunzo ya Ujasiriamali',
            'Mafunzo ya Uongozi wa Jamii',
            'Mafunzo ya Haki za Binadamu',
            'Mafunzo ya Jinsia na Maendeleo',
            'Mafunzo ya Mazingira',
            'Mafunzo ya Uwezeshaji wa Vijana',
            'Mafunzo ya Uwezeshaji wa Wanawake',
            'Mafunzo ya Data Management',
            'Mafunzo ya M&E',
            'Mafunzo ya Report Writing',
            'Mafunzo ya Communication Skills',
            'Mafunzo ya Leadership Skills',
        ];

        $trainerTypes = ['internal', 'external'];
        $year = date('Y');
        $created = 0;

        // Tafuta departments na employees
        $departmentIds = Department::pluck('id')->toArray();

        foreach ($trainingTitles as $title) {
            $trainingNumber = 'TRN-' . $year . '-' . str_pad((\DB::table('trainings')->max('id') + 1), 4, '0', STR_PAD_LEFT);

            if (\DB::table('trainings')->where('training_number', $trainingNumber)->exists()) {
                continue;
            }

            $category = $categories[array_rand($categories)];
            $status = $statuses[array_rand($statuses)];
            $startDate = now()->subDays(rand(1, 180));
            $endDate = (clone $startDate)->addDays(rand(1, 5));

            \DB::table('trainings')->insert([
                'training_number'   => $trainingNumber,
                'title'             => $title,
                'description'       => 'Mafunzo ya SAPTA: ' . $title,
                'category'          => $category,
                'trainer_name'      => 'Mwalimu ' . ['John', 'Mary', 'Peter', 'Halima', 'Joseph'][array_rand([0,1,2,3,4])],
                'trainer_type'      => $trainerTypes[array_rand($trainerTypes)],
                'trainer_contact'   => '07' . rand(10000000, 99999999),
                'location'          => ['Dar es Salaam', 'Morogoro', 'Dodoma', 'Mwanza', 'Arusha'][array_rand([0,1,2,3,4])],
                'start_date'        => $startDate->format('Y-m-d'),
                'end_date'          => $endDate->format('Y-m-d'),
                'start_time'        => '08:00:00',
                'end_time'          => '17:00:00',
                'duration_hours'    => rand(4, 40),
                'max_participants'  => rand(10, 50),
                'cost'              => rand(100000, 5000000),
                'currency'          => 'TZS',
                'department_id'     => $departmentIds[array_rand($departmentIds)] ?? 1,
                'region_id'         => rand(1, 31),
                'district_id'       => rand(1, 61),
                'ward_id'           => rand(1, 900),
                'status'            => $status,
                'created_by'        => $adminId,
                'notes'             => 'Mafunzo ya ' . $title,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
            $created++;
        }

        echo "✅ Trainings zilizoundwa: {$created}" . PHP_EOL;
        echo "   Jumla trainings: " . \DB::table('trainings')->count() . PHP_EOL;
    }
}