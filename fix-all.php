<?php
$file = 'resources/views/dashboard/index.blade.php';
$content = file_get_contents($file);

// Rejesha ?? zote
$content = str_replace("\$emp->email  'No email'", "\$emp->email ?? 'No email'", $content);
$content = str_replace("\$t->trainer_name  'No trainer'", "\$t->trainer_name ?? 'No trainer'", $content);
$content = str_replace("\$dept->code  ''", "\$dept->code ?? ''", $content);

file_put_contents($file, $content);
echo "Fixed all ?? in dashboard\n";
