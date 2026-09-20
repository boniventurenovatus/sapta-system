<?php
$file = 'resources/views/organizations/index.blade.php';
$content = file_get_contents($file);

// Rejesha ?? kwenye organizations
$content = str_replace("\$org->type - 'headquarters'", "\$org->type ?? 'headquarters'", $content);
$content = str_replace("\$org->parent->name - '?'", "\$org->parent->name ?? '?'", $content);
$content = str_replace("\$org->city - '?'", "\$org->city ?? '?'", $content);

file_put_contents($file, $content);
echo "Fixed organizations index\n";
