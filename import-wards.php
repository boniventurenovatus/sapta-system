<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Region;
use App\Models\District;
use App\Models\Ward;

if (!file_exists(__DIR__.'/wards.csv')) {
    die("ERROR: wards.csv haipo!" . PHP_EOL);
}

$csv = array_map('str_getcsv', file(__DIR__.'/wards.csv'));
$header = array_shift($csv);

$regionMap = [
    'morogoro' => 'Morogoro',
    'ruvuma'   => 'Ruvuma',
    'arusha'   => 'Arusha',
    'dodoma'   => 'Dodoma',
];

$stats = ['regions' => 0, 'districts_new' => 0, 'districts_existing' => 0, 'wards_new' => 0, 'wards_existing' => 0];
$grouped = [];

foreach ($csv as $row) {
    if (count($row) < 3) continue;
    [$regionSlug, $districtName, $wardName] = $row;
    $grouped[$regionSlug][$districtName][] = $wardName;
}

foreach ($grouped as $regionSlug => $districts) {
    $regionName = $regionMap[$regionSlug] ?? ucfirst($regionSlug);
    $region = Region::where('name', 'like', "%{$regionName}%")->first();

    if (!$region) {
        echo "Region haipo: {$regionName} ? SKIP" . PHP_EOL;
        continue;
    }
    $stats['regions']++;
    echo "Region: {$region->name} (ID={$region->id})" . PHP_EOL;

    foreach ($districts as $districtName => $wards) {
        $district = District::where('region_id', $region->id)
            ->where('name', 'like', "%{$districtName}%")
            ->first();

        if ($district) {
            $stats['districts_existing']++;
        } else {
            $district = District::create([
                'region_id' => $region->id,
                'name' => $districtName,
                'code' => strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $districtName), 0, 8)),
                'status' => 'active',
            ]);
            $stats['districts_new']++;
        }

        foreach ($wards as $wardName) {
            $ward = Ward::where('district_id', $district->id)
                ->where('name', $wardName)
                ->first();

            if ($ward) {
                $stats['wards_existing']++;
            } else {
                Ward::create([
                    'district_id' => $district->id,
                    'name' => $wardName,
                    'code' => strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $wardName), 0, 8)),
                    'status' => 'active',
                ]);
                $stats['wards_new']++;
            }
        }
        echo "  {$districtName}: " . count($wards) . " wards" . PHP_EOL;
    }
}

echo PHP_EOL . "=== IMPORT COMPLETE ===" . PHP_EOL;
echo "Regions zilizopatikana: {$stats['regions']}" . PHP_EOL;
echo "Districts mpya: {$stats['districts_new']}" . PHP_EOL;
echo "Districts zilizopo: {$stats['districts_existing']}" . PHP_EOL;
echo "Wards mpya: {$stats['wards_new']}" . PHP_EOL;
echo "Wards zilizopo: {$stats['wards_existing']}" . PHP_EOL;

echo PHP_EOL . "=== TOTAL COUNTS SASA ===" . PHP_EOL;
echo "Regions: " . Region::count() . PHP_EOL;
echo "Districts: " . District::count() . PHP_EOL;
echo "Wards: " . Ward::count() . PHP_EOL;
