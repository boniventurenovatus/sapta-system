<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;
use App\Models\District;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            'DS' => [
                ['name' => 'Kinondoni', 'code' => 'DS-KIN'],
                ['name' => 'Ilala', 'code' => 'DS-ILA'],
                ['name' => 'Temeke', 'code' => 'DS-TEM'],
                ['name' => 'Ubungo', 'code' => 'DS-UBU'],
                ['name' => 'Kigamboni', 'code' => 'DS-KIG'],
            ],
            'AR' => [
                ['name' => 'Arusha Mjini', 'code' => 'AR-ARM'],
                ['name' => 'Arusha Vijijini', 'code' => 'AR-ARV'],
                ['name' => 'Karatu', 'code' => 'AR-KAR'],
                ['name' => 'Longido', 'code' => 'AR-LON'],
                ['name' => 'Meru', 'code' => 'AR-MER'],
                ['name' => 'Monduli', 'code' => 'AR-MON'],
                ['name' => 'Ngorongoro', 'code' => 'AR-NGO'],
            ],
            'DD' => [
                ['name' => 'Dodoma Mjini', 'code' => 'DD-DOM'],
                ['name' => 'Bahi', 'code' => 'DD-BAH'],
                ['name' => 'Chamwino', 'code' => 'DD-CHA'],
                ['name' => 'Chemba', 'code' => 'DD-CHE'],
                ['name' => 'Kondoa', 'code' => 'DD-KON'],
                ['name' => 'Kongwa', 'code' => 'DD-KGW'],
                ['name' => 'Mpwapwa', 'code' => 'DD-MPW'],
            ],
            'IR' => [
                ['name' => 'Iringa Mjini', 'code' => 'IR-IRM'],
                ['name' => 'Iringa Vijijini', 'code' => 'IR-IRV'],
                ['name' => 'Kilolo', 'code' => 'IR-KIL'],
                ['name' => 'Mafinga', 'code' => 'IR-MAF'],
                ['name' => 'Mufindi', 'code' => 'IR-MUF'],
            ],
            'MW' => [
                ['name' => 'Ilemela', 'code' => 'MW-ILE'],
                ['name' => 'Nyamagana', 'code' => 'MW-NYA'],
                ['name' => 'Buchosa', 'code' => 'MW-BUC'],
                ['name' => 'Kwimba', 'code' => 'MW-KWI'],
                ['name' => 'Magu', 'code' => 'MW-MAG'],
                ['name' => 'Misungwi', 'code' => 'MW-MIS'],
                ['name' => 'Sengerema', 'code' => 'MW-SEN'],
                ['name' => 'Ukerewe', 'code' => 'MW-UKE'],
            ],
        ];

        $count = 0;
        foreach ($districts as $regionCode => $regionDistricts) {
            $region = Region::where('code', $regionCode)->first();
            if (!$region) {
                continue;
            }

            foreach ($regionDistricts as $district) {
                District::updateOrCreate(
                    ['code' => $district['code']],
                    [
                        'region_id' => $region->id,
                        'name' => $district['name'],
                        'code' => $district['code'],
                        'status' => 'active',
                    ]
                );
                $count++;
            }
        }

        $this->command->info("Districts seeded: $count");
    }
}
