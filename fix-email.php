<?php
$file = 'resources/views/dashboard/index.blade.php';
$content = file_get_contents($file);

// Rejesha ?? kwenye $emp->email
$content = str_replace("{{ `$emp->email  'No email' }}", "{{ `$emp->email ?? 'No email' }}", $content);

file_put_contents($file, $content);
echo "Fixed emp->email\n";
