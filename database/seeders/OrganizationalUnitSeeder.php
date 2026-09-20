<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrganizationalUnit;

class OrganizationalUnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['name' => 'Board of Directors', 'code' => 'BOD', 'parent_id' => null, 'description' => 'Board of Directors'],
            ['name' => 'Chief Executive Officer', 'code' => 'CEO', 'parent_id' => null, 'description' => 'Chief Executive Officer'],
            ['name' => 'Administrative and Operations Department', 'code' => 'ADMIN', 'parent_id' => null, 'description' => 'Administrative and Operations'],
            ['name' => 'Program and Technical Department', 'code' => 'PROG', 'parent_id' => null, 'description' => 'Program and Technical'],
            ['name' => 'Monitoring, Evaluation, Accountability and Learning Department', 'code' => 'MEAL', 'parent_id' => null, 'description' => 'MEAL Department'],
            ['name' => 'Communications & ICT / Digital Innovation Department', 'code' => 'ICT', 'parent_id' => null, 'description' => 'Communications & ICT'],
        ];

        foreach ($units as $unit) {
            OrganizationalUnit::updateOrCreate(
                ['code' => $unit['code']],
                $unit
            );
        }
    }
}
