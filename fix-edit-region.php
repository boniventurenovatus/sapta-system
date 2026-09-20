<?php
$file = 'resources/views/organizations/edit.blade.php';
$content = file_get_contents($file);

// Badilisha Region input kuwa dropdown
$oldRegion = '<input type="text" name="region" value="{{ old(\'region\', $organization->region) }}" class="org-edit-input">';

$newRegion = '<select name="region" class="org-edit-input">
                            <option value="">Select Region</option>
                            @foreach([\'Arusha\',\'Dar es Salaam\',\'Dodoma\',\'Geita\',\'Iringa\',\'Kagera\',\'Katavi\',\'Kigoma\',\'Kilimanjaro\',\'Lindi\',\'Manyara\',\'Mara\',\'Mbeya\',\'Morogoro\',\'Mtwara\',\'Mwanza\',\'Njombe\',\'Pemba North\',\'Pemba South\',\'Pwani\',\'Rukwa\',\'Ruvuma\',\'Shinyanga\',\'Simiyu\',\'Singida\',\'Songwe\',\'Tabora\',\'Tanga\',\'Zanzibar North\',\'Zanzibar South\',\'Zanzibar Urban\'] as $region)
                                <option value="{{ $region }}" @selected(old(\'region\', $organization->region) === $region)>{{ $region }}</option>
                            @endforeach
                        </select>';

$content = str_replace($oldRegion, $newRegion, $content);

file_put_contents($file, $content);
echo "Fixed Region dropdown in edit\n";
