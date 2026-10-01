<?php
$file = "app/Console/Commands/SaptaSetup.php";
$content = file_get_contents($file);

// Badilisha sehemu ya sequence — tumia TRUNCATE ... RESTART IDENTITY kwa PostgreSQL
$old = "if (\$driver === 'pgsql') {
                DB::statement(\"SELECT setval('organizations_id_seq', 1, false);\");
                DB::statement(\"SELECT setval('departments_id_seq', 1, false);\");
                DB::statement(\"SELECT setval('positions_id_seq', 1, false);\");
                DB::statement(\"SELECT setval('employees_id_seq', 1, false);\");
                DB::statement(\"SELECT setval('users_id_seq', 1, false);\");
            }";

$new = "if (\$driver === 'pgsql') {
                // Tumia TRUNCATE ... RESTART IDENTITY kwa PostgreSQL
                DB::statement('TRUNCATE TABLE organizations RESTART IDENTITY CASCADE;');
                DB::statement('TRUNCATE TABLE departments RESTART IDENTITY CASCADE;');
                DB::statement('TRUNCATE TABLE positions RESTART IDENTITY CASCADE;');
                DB::statement('TRUNCATE TABLE employees RESTART IDENTITY CASCADE;');
                DB::statement('TRUNCATE TABLE users RESTART IDENTITY CASCADE;');
            }";

if (strpos($content, $old) === false) {
    echo "[ERROR] Block haipatikani\n";
    exit;
}

$content = str_replace($old, $new, $content);
file_put_contents($file, $content);
echo "[OK] Command imebadilishwa\n";
