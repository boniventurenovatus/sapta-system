<?php
$file = 'resources/views/dashboard/index.blade.php';
$content = file_get_contents($file);

// Badilisha Kiswahili kuwa English
$content = str_replace('Karibu,', 'Welcome,', $content);
$content = str_replace('Hapa ni muhtasari wa SAPTA Management System.', 'Here is an overview of SAPTA Management System.', $content);
$content = str_replace('??', '??', $content);

file_put_contents($file, $content);
echo "Fixed dashboard language\n";
