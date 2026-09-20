<?php
$file = 'resources/views/organizations/index.blade.php';
$content = file_get_contents($file);

// Split kwa lines
$lines = explode("\n", $content);
$fixed = 0;

foreach ($lines as $i => $line) {
    // Kama line ina 'org->parent->name ?? '?''
    if (strpos($line, "org->parent->name ?? '?'") !== false) {
        $lines[$i] = str_replace("org->parent->name ?? '?'", "org->parent->name ?? '?'", $line);
        $fixed++;
    }
    // Kama line ina 'org->city ?? '?''
    if (strpos($line, "org->city ?? '?'") !== false) {
        $lines[$i] = str_replace("org->city ?? '?'", "org->city ?? '?'", $line);
        $fixed++;
    }
}

file_put_contents($file, implode("\n", $lines));
echo "Fixed: $fixed lines\n";

// Angalia
$content = file_get_contents($file);
if (strpos($content, "?? '?'") !== false) {
    echo "FAIL: ?? '?' STILL EXISTS\n";
} else {
    echo "SUCCESS: ?? '?' REMOVED\n";
}
