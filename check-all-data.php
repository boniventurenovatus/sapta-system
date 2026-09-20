<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Organization;
use App\Models\Department;
use App\Models\OrganizationalUnit;
use App\Models\Position;
use App\Models\Employee;

echo "=== ORGANIZATIONS ===\n";
foreach (Organization::all() as $o) {
    echo $o->id . ' | ' . $o->name . ' | code: ' . ($o->code ?? '?') . "\n";
}

echo "\n=== DEPARTMENTS ===\n";
foreach (Department::all() as $d) {
    echo $d->id . ' | ' . $d->name . ' | org_id: ' . $d->organization_id . "\n";
}

echo "\n=== ORGANIZATIONAL UNITS ===\n";
foreach (OrganizationalUnit::all() as $u) {
    echo $u->id . ' | ' . $u->name . ' | code: ' . ($u->code ?? '?') . "\n";
}

echo "\n=== POSITIONS ===\n";
foreach (Position::all() as $p) {
    echo $p->id . ' | ' . $p->title . ' | code: ' . $p->code . "\n";
}

echo "\n=== EMPLOYEES ===\n";
foreach (Employee::all() as $e) {
    echo $e->id . ' | ' . $e->first_name . ' ' . $e->last_name . "\n";
}
