<?php
$file = 'resources/views/organizations/edit.blade.php';
$content = file_get_contents($file);

// Ondoa multiple na size
$content = str_replace(
    '<select name="region" class="org-edit-input" multiple>',
    '<select name="region" class="org-edit-input">',
    $content
);
$content = str_replace(
    '<select name="region" class="org-edit-input" size="5">',
    '<select name="region" class="org-edit-input">',
    $content
);

file_put_contents($file, $content);
echo "Removed multiple/size\n";
