<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslateDataToEnglishSeeder extends Seeder
{
    public function run(): void
    {
        echo "=== TRANSLATING ALL DATA TO ENGLISH ===" . PHP_EOL . PHP_EOL;

        // ================================================================
        // 1. TASKS — Titles
        // ================================================================
        $taskTranslations = [
            'Kutembelea wilaya na kukusanya data' => 'Visit districts and collect data',
            'Kuandaa ripoti ya mwezi' => 'Prepare monthly report',
            'Kufanya mafunzo kwa wakulima' => 'Conduct training for farmers',
            'Kutafuta washirika wapya' => 'Find new partners',
            'Kuandaa bajeti ya robo mwaka' => 'Prepare quarterly budget',
            'Kufanya tathmini ya mradi' => 'Conduct project evaluation',
            'Kutembelea shule na kufanya usajili' => 'Visit schools and conduct registration',
            'Kusambaza vifaa vya mafunzo' => 'Distribute training materials',
            'Kufanya mkutano na viongozi wa vijiji' => 'Meet with village leaders',
            'Kuandaa waraka wa mafunzo' => 'Prepare training document',
            'Kutengeneza video ya uhamasishaji' => 'Create awareness video',
            'Kufanya usajili wa walengwa' => 'Register beneficiaries',
            'Kukusanya data ya awali' => 'Collect baseline data',
            'Kuandaa semina ya wadau' => 'Prepare stakeholder seminar',
            'Kufanya ukaguzi wa mradi' => 'Conduct project audit',
            'Kuandaa ripoti ya tathmini' => 'Prepare evaluation report',
            'Kutengeneza mabango ya elimu' => 'Create educational posters',
            'Kufanya usaili wa wafanyakazi' => 'Conduct staff interviews',
            'Kuandaa mpango wa mwaka' => 'Prepare annual plan',
            'Kutembelea miradi shirikishi' => 'Visit partner projects',
            'Kufanya mikutano ya jamii' => 'Conduct community meetings',
            'Kusambaza mbegu bora' => 'Distribute quality seeds',
            'Kufanya mafunzo ya ujasiriamali' => 'Conduct entrepreneurship training',
            'Kutengeneza mwongozo wa mafunzo' => 'Create training manual',
            'Kufanya uchunguzi wa soko' => 'Conduct market research',
        ];

        $tasksUpdated = 0;
        foreach ($taskTranslations as $swahili => $english) {
            $count = DB::table('tasks')->where('title', $swahili)->update(['title' => $english]);
            $tasksUpdated += $count;
        }
        echo "OK: Tasks titles: {$tasksUpdated}" . PHP_EOL;

        // Update task descriptions — replace any Swahili pattern
        $allTasks = DB::table('tasks')->get(['id', 'title', 'description']);
        foreach ($allTasks as $task) {
            $newDesc = 'Task for: ' . $task->title;
            DB::table('tasks')->where('id', $task->id)->update(['description' => $newDesc]);
        }
        echo "OK: Task descriptions updated" . PHP_EOL;

        // ================================================================
        // 2. PROJECTS — Names
        // ================================================================
        $projectTranslations = [
            'Kilimo Endelevu kwa Vijana' => 'Sustainable Agriculture for Youth',
            'Elimu ya Afya kwa Wanawake' => 'Health Education for Women',
            'Upatikanaji wa Maji Safi - Singida' => 'Clean Water Access - Singida',
            'Uwezeshaji wa Wanawake kiuchumi' => 'Women Economic Empowerment',
            'Mafunzo ya Ujasiriamali kwa Vijana' => 'Entrepreneurship Training for Youth',
            'Kilimo cha Umwagiliaji - Morogoro' => 'Irrigation Agriculture - Morogoro',
            'Afya ya Mama na Mtoto - Mwanza' => 'Maternal and Child Health - Mwanza',
            'Elimu ya Msingi - Boresha Ubora' => 'Primary Education - Quality Improvement',
            'Mazingira na Uhifadhi wa Misitu' => 'Environment and Forest Conservation',
            'Ufugaji wa Kisasa - Mbeya' => 'Modern Livestock Farming - Mbeya',
            'TEHAMA kwa Shule za Vijijini' => 'ICT for Rural Schools',
            'Ushirikiano wa Jinsia na Haki' => 'Gender Equality and Justice',
            'Mikopo Midogo kwa Wajasiriamali' => 'Micro Loans for Entrepreneurs',
            'Kupambana na Ukimwi - Vijana' => 'Fighting HIV/AIDS - Youth',
            'Lishe Bora kwa Watoto' => 'Better Nutrition for Children',
            'Mafunzo ya Ufundi kwa Vijana' => 'Vocational Training for Youth',
            'Hifadhi ya Mazingira - Pwani' => 'Environmental Conservation - Coastal',
            'Ufuatiliaji wa Lishe - Shule' => 'Nutrition Monitoring - Schools',
            'Kilimo cha Biashara - Ruvuma' => 'Commercial Agriculture - Ruvuma',
            'Uwezeshaji wa Vijana - Mtwara' => 'Youth Empowerment - Mtwara',
        ];

        $projectsUpdated = 0;
        foreach ($projectTranslations as $swahili => $english) {
            $count = DB::table('projects')->where('name', $swahili)->update(['name' => $english]);
            $projectsUpdated += $count;
        }
        echo "OK: Projects names: {$projectsUpdated}" . PHP_EOL;

        // Update ALL project descriptions
        $projects = DB::table('projects')->get(['id', 'name']);
        foreach ($projects as $project) {
            DB::table('projects')->where('id', $project->id)->update([
                'description' => 'SAPTA Project: ' . $project->name
            ]);
        }
        echo "OK: Project descriptions updated" . PHP_EOL;

        // ================================================================
        // 3. BUDGETS — Names + Notes
        // ================================================================
        $budgets = DB::table('budgets')->get(['id', 'name', 'project_id', 'notes']);
        foreach ($budgets as $budget) {
            $project = DB::table('projects')->where('id', $budget->project_id)->first();
            $projectName = $project ? $project->name : 'Project';
            
            // Extract category from name (after last dash)
            $parts = explode('-', $budget->name);
            $category = trim(end($parts));
            
            $newName = $projectName . ' - ' . $category;
            
            DB::table('budgets')->where('id', $budget->id)->update([
                'name' => $newName,
                'notes' => 'Budget for fiscal year 2026',
            ]);
        }
        echo "OK: Budgets updated" . PHP_EOL;

        // ================================================================
        // 4. TRAININGS — Titles + Descriptions
        // ================================================================
        $trainingTranslations = [
            'Mafunzo ya Uongozi Bora' => 'Leadership Training',
            'Mafunzo ya Usimamizi wa Miradi' => 'Project Management Training',
            'Mafunzo ya TEHAMA' => 'ICT Training',
            'Mafunzo ya Uandishi wa Ripoti' => 'Report Writing Training',
            'Mafunzo ya Mawasiliano' => 'Communication Skills Training',
            'Mafunzo ya Usalama Kazini' => 'Workplace Safety Training',
            'Mafunzo ya Fedha kwa Wasio Fedha' => 'Finance for Non-Finance Training',
            'Mafunzo ya Ukaguzi wa Ndani' => 'Internal Audit Training',
            'Mafunzo ya Usimamizi wa Rasilimali Watu' => 'HR Management Training',
            'Mafunzo ya Ufuatiliaji na Tathmini' => 'M&E Training',
            'Mafunzo ya Ununuzi' => 'Procurement Training',
            'Mafunzo ya Kilimo Bora' => 'Good Agricultural Practices Training',
            'Mafunzo ya Afya na Usalama' => 'Health and Safety Training',
            'Mafunzo ya Ujasiriamali' => 'Entrepreneurship Training',
            'Mafunzo ya Uongozi wa Jamii' => 'Community Leadership Training',
            'Mafunzo ya Haki za Binadamu' => 'Human Rights Training',
            'Mafunzo ya Jinsia na Maendeleo' => 'Gender and Development Training',
            'Mafunzo ya Mazingira' => 'Environmental Training',
            'Mafunzo ya Uwezeshaji wa Vijana' => 'Youth Empowerment Training',
            'Mafunzo ya Uwezeshaji wa Wanawake' => 'Women Empowerment Training',
            'Mafunzo ya Data Management' => 'Data Management Training',
            'Mafunzo ya M&E' => 'M&E Training',
            'Mafunzo ya Report Writing' => 'Report Writing Training',
            'Mafunzo ya Communication Skills' => 'Communication Skills Training',
            'Mafunzo ya Leadership Skills' => 'Leadership Skills Training',
        ];

        $trainingsUpdated = 0;
        foreach ($trainingTranslations as $swahili => $english) {
            $count = DB::table('trainings')->where('title', $swahili)->update(['title' => $english]);
            $trainingsUpdated += $count;
        }
        echo "OK: Trainings titles: {$trainingsUpdated}" . PHP_EOL;

        // Update ALL training descriptions + notes
        $trainings = DB::table('trainings')->get(['id', 'title']);
        foreach ($trainings as $training) {
            DB::table('trainings')->where('id', $training->id)->update([
                'description' => 'SAPTA Training: ' . $training->title,
                'notes' => 'Training for staff development',
            ]);
        }
        echo "OK: Training descriptions updated" . PHP_EOL;

        // ================================================================
        // 5. DOCUMENTS — Titles + Descriptions
        // ================================================================
        $docTranslations = [
            'Mkataba wa Mradi' => 'Project Contract',
            'Ripoti ya Mwezi' => 'Monthly Report',
            'Ripoti ya Robo Mwaka' => 'Quarterly Report',
            'Ripoti ya Mwaka' => 'Annual Report',
            'Pendekezo la Mradi' => 'Project Proposal',
            'Sera ya Mradi' => 'Project Policy',
            'Mkakati wa Utekelezaji' => 'Implementation Strategy',
            'Barua ya Makubaliano' => 'Agreement Letter',
            'Ripoti ya Tathmini' => 'Evaluation Report',
            'Ripoti ya Ufuatiliaji' => 'Monitoring Report',
            'Mpango wa Mafunzo' => 'Training Plan',
            'Mwongozo wa Mafunzo' => 'Training Manual',
            'Ripoti ya Ukaguzi' => 'Audit Report',
            'Hati ya Ushirikiano' => 'Partnership Document',
            'Ripoti ya Fedha' => 'Financial Report',
            'Bajeti ya Mradi' => 'Project Budget',
            'Mkataba wa Washirika' => 'Partner Agreement',
            'Barua ya Idhini' => 'Approval Letter',
            'Ripoti ya Semina' => 'Seminar Report',
            'Hati ya Mafunzo' => 'Training Document',
        ];

        $docsUpdated = 0;
        foreach ($docTranslations as $swahili => $english) {
            $count = DB::table('documents')->where('title', 'LIKE', "%{$swahili}%")->count();
            if ($count > 0) {
                DB::table('documents')->where('title', 'LIKE', "%{$swahili}%")->update([
                    'title' => DB::raw("REPLACE(title, '{$swahili}', '{$english}')")
                ]);
                $docsUpdated += $count;
            }
        }
        echo "OK: Documents titles: {$docsUpdated}" . PHP_EOL;

        // Update ALL document descriptions + notes
        $documents = DB::table('documents')->get(['id', 'project_id']);
        foreach ($documents as $doc) {
            $project = DB::table('projects')->where('id', $doc->project_id)->first();
            $projectName = $project ? $project->name : 'SAPTA';
            DB::table('documents')->where('id', $doc->id)->update([
                'description' => 'Document for: ' . $projectName,
            ]);
        }
        echo "OK: Document descriptions updated" . PHP_EOL;

        // ================================================================
        // 6. LEAVE REQUESTS — Reasons (kama zina Kiswahili)
        // ================================================================
        if (\Schema::hasColumn('leave_requests', 'reason')) {
            $leaves = DB::table('leave_requests')->get(['id', 'reason']);
            foreach ($leaves as $leave) {
                if ($leave->reason && preg_match('/[Kk]u|[Mm]a|[Nn]i/', $leave->reason)) {
                    DB::table('leave_requests')->where('id', $leave->id)->update([
                        'reason' => 'Personal reasons'
                    ]);
                }
            }
            echo "OK: Leave requests updated" . PHP_EOL;
        }

        // ================================================================
        // 7. EMPLOYEES — Job Titles (kama zina Kiswahili)
        // ================================================================
        // Angalia kama kuna job titles za Kiswahili
        $employees = DB::table('employees')->get(['id', 'job_title']);
        foreach ($employees as $emp) {
            $jobTitle = $emp->job_title;
            if ($jobTitle && preg_match('/Msimamizi|Mwalimu|Mhazini|Karani|Dereva/', $jobTitle)) {
                $newTitle = str_replace(
                    ['Msimamizi', 'Mwalimu', 'Mhazini', 'Karani', 'Dereva'],
                    ['Manager', 'Teacher', 'Accountant', 'Clerk', 'Driver'],
                    $jobTitle
                );
                DB::table('employees')->where('id', $emp->id)->update(['job_title' => $newTitle]);
            }
        }
        echo "OK: Employee job titles updated" . PHP_EOL;

        echo PHP_EOL . "=== TRANSLATION COMPLETE ===" . PHP_EOL;
    }
}