<?php
$file = 'resources/views/organizations/edit.blade.php';
$content = file_get_contents($file);

$content = str_replace(
    '<select name="region" class="org-edit-input">',
    '<select name="region" class="org-edit-input" size="1">',
    $content
);

file_put_contents($file, $content);
echo "Added size=1 to Region select\n";
