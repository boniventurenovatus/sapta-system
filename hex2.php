<?php
$file = 'resources/views/organizations/index.blade.php';
$content = file_get_contents($file);

$lines = explode("\n", $content);
foreach ($lines as $line) {
    if (strpos($line, 'org->parent->name') !== false) {
        echo "LENGTH: " . strlen($line) . "\n";
        echo "HEX: " . bin2hex($line) . "\n";
        echo "LINE: " . $line . "\n";
    }
}
