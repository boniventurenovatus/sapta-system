<?php
$file = "routes/web.php";
$content = file_get_contents($file);

// Ondoa Organization::withTrashed()->forceDelete() kwenye setup
$old = "// 2. Ondoa organizations za zamani
        \\App\\Models\\Organization::withTrashed()->forceDelete();
        \$log[] = \"Organizations zimeondolewa\";";

$new = "// 2. Ondoa organizations za zamani — lakini tuache departments
        // (departments zinahusiana na organization — tutazirekebisha)
        \$oldOrg = \\App\\Models\\Organization::withTrashed()->first();
        if (\$oldOrg) {
            \\App\\Models\\Department::where('organization_id', \$oldOrg->id)->update(['organization_id' => null]);
            \\App\\Models\\Organization::withTrashed()->forceDelete();
        }
        \$log[] = \"Organizations zimeondolewa\";";

$content = str_replace($old, $new, $content);
file_put_contents($file, $content);
echo "[OK] Route imebadilishwa\n";
