<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Position;
use App\Models\OrganizationalUnit;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $units = OrganizationalUnit::pluck('id', 'code')->toArray();

        $positions = [
            // Board of Directors
            ['title' => 'Board of Directors', 'code' => 'BOD-001', 'unit' => 'BOD'],

            // CEO
            ['title' => 'Chief Executive Officer (CEO)', 'code' => 'CEO-001', 'unit' => 'CEO'],

            // Administrative and Operations Department
            ['title' => 'Administrative Director', 'code' => 'ADM-001', 'unit' => 'ADMIN'],
            ['title' => 'Human Resource Management & Administration Manager', 'code' => 'ADM-002', 'unit' => 'ADMIN'],
            ['title' => 'Procurement & Logistics Manager', 'code' => 'ADM-003', 'unit' => 'ADMIN'],
            ['title' => 'Finance Manager', 'code' => 'ADM-004', 'unit' => 'ADMIN'],
            ['title' => 'Accountant', 'code' => 'ADM-005', 'unit' => 'ADMIN'],

            // Program and Technical Department
            ['title' => 'Program & Technical Director', 'code' => 'PRG-001', 'unit' => 'PROG'],
            ['title' => 'Project Manager', 'code' => 'PRG-002', 'unit' => 'PROG'],
            ['title' => 'Project Officers', 'code' => 'PRG-003', 'unit' => 'PROG'],
            ['title' => 'Field Trainer', 'code' => 'PRG-004', 'unit' => 'PROG'],
            ['title' => 'Partnerships and Resource Mobilization Manager', 'code' => 'PRG-005', 'unit' => 'PROG'],

            // MEAL
            ['title' => 'MEAL Manager', 'code' => 'MEL-001', 'unit' => 'MEAL'],
            ['title' => 'MEAL Officer', 'code' => 'MEL-002', 'unit' => 'MEAL'],
            ['title' => 'Research and Innovation Officer', 'code' => 'MEL-003', 'unit' => 'MEAL'],

            // Communications & ICT
            ['title' => 'Community Knowledge Manager', 'code' => 'ICT-001', 'unit' => 'ICT'],
            ['title' => 'ICT & Digital Innovation Manager', 'code' => 'ICT-002', 'unit' => 'ICT'],
        ];

        foreach ($positions as $pos) {
            Position::updateOrCreate(
                ['code' => $pos['code']],
                [
                    'title' => $pos['title'],
                    'code' => $pos['code'],
                    'organizational_unit_id' => $units[$pos['unit']] ?? null,
                    'status' => 'active',
                    'description' => $pos['title'] . ' at SAPTA',
                ]
            );
        }
    }
}
