<?php
$file = 'resources/views/organizations/index.blade.php';
$lines = file($file);

foreach ($lines as $i => $line) {
    if (strpos($line, "?? '?'") !== false) {
        $lines[$i] = str_replace("?? '?'", "?? '?'", $line);
    }
}

file_put_contents($file, implode('', $lines));
echo "Fixed with line-based replace\n";
