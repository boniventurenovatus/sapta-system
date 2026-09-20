<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['name' => 'Arusha', 'code' => 'AR'],
            ['name' => 'Dar es Salaam', 'code' => 'DS'],
            ['name' => 'Dodoma', 'code' => 'DD'],
            ['name' => 'Geita', 'code' => 'GT'],
            ['name' => 'Iringa', 'code' => 'IR'],
            ['name' => 'Kagera', 'code' => 'KG'],
            ['name' => 'Katavi', 'code' => 'KT'],
            ['name' => 'Kigoma', 'code' => 'KM'],
            ['name' => 'Kilimanjaro', 'code' => 'KL'],
            ['name' => 'Lindi', 'code' => 'LD'],
            ['name' => 'Manyara', 'code' => 'MY'],
            ['name' => 'Mara', 'code' => 'MR'],
            ['name' => 'Mbeya', 'code' => 'MB'],
            ['name' => 'Morogoro', 'code' => 'MG'],
            ['name' => 'Mtwara', 'code' => 'MT'],
            ['name' => 'Mwanza', 'code' => 'MW'],
            ['name' => 'Njombe', 'code' => 'NJ'],
            ['name' => 'Pemba North', 'code' => 'PN'],
            ['name' => 'Pemba South', 'code' => 'PS'],
            ['name' => 'Pwani', 'code' => 'PW'],
            ['name' => 'Rukwa', 'code' => 'RK'],
            ['name' => 'Ruvuma', 'code' => 'RV'],
            ['name' => 'Shinyanga', 'code' => 'SH'],
            ['name' => 'Simiyu', 'code' => 'SM'],
            ['name' => 'Singida', 'code' => 'SD'],
            ['name' => 'Songwe', 'code' => 'SW'],
            ['name' => 'Tabora', 'code' => 'TB'],
            ['name' => 'Tanga', 'code' => 'TG'],
            ['name' => 'Zanzibar North', 'code' => 'ZN'],
            ['name' => 'Zanzibar South', 'code' => 'ZS'],
            ['name' => 'Zanzibar Urban', 'code' => 'ZU'],
        ];

        foreach ($regions as $region) {
            Region::updateOrCreate(
                ['code' => $region['code']],
                $region
            );
        }

        $this->command->info('Regions seeded: ' . Region::count());
    }
}
