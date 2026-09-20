<?php
$file = 'resources/views/organizations/edit.blade.php';
$content = file_get_contents($file);

// Futa duplicate ya pili (option + foreach + select)
$oldDuplicate = '                              <option value="">Select Region</option>
                              @foreach([\'Arusha\',\'Dar es Salaam\',\'Dodoma\',\'Geita\',\'Iringa\',\'Kagera\',\'Katavi\',\'Kigoma\',\'Kilimanjaro\',\'Lindi\',\'Manyara\',\'Mara\',\'Mbeya\',\'Morogoro\',\'Mtwara\',\'Mwanza\',\'Njombe\',\'Pemba North\',\'Pemba South\',\'Pwani\',\'Rukwa\',\'Ruvuma\',\'Shinyanga\',\'Simiyu\',\'Singida\',\'Songwe\',\'Tabora\',\'Tanga\',\'Zanzibar North\',\'Zanzibar South\',\'Zanzibar Urban\'] as $region)
                                  <option value="{{ $region }}" @selected(old(\'region\', $organization->region) === $region)>{{ $region }}</option>
                              @endforeach
                          </select>';

$content = str_replace($oldDuplicate, '', $content);

file_put_contents($file, $content);
echo "Removed duplicate\n";
