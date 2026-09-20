<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Department;

$departments = [
    ['name' => 'Board of Directors', 'code' => 'BOD', 'organization_id' => 2],
    ['name' => 'Chief Executive Officer', 'code' => 'CEO', 'organization_id' => 2],
    ['name' => 'Administrative and Operations Department', 'code' => 'ADMIN', 'organization_id' => 2],
    ['name' => 'Program and Technical Department', 'code' => 'PROG', 'organization_id' => 2],
    ['name' => 'Monitoring, Evaluation, Accountability and Learning Department', 'code' => 'MEAL', 'organization_id' => 2],
    ['name' => 'Communications & ICT / Digital Innovation Department', 'code' => 'ICT', 'organization_id' => 2],
];

foreach ($departments as $dept) {
    Department::updateOrCreate(
        ['code' => $dept['code']],
        $dept
    );
}

echo "=== DEPARTMENTS ZOTE ===\n";
foreach (Department::all() as $d) {
    echo $d->id . ' | ' . $d->name . ' | code: ' . $d->code . "\n";
}
