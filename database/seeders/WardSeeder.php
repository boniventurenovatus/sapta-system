<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\District;
use App\Models\Ward;

class WardSeeder extends Seeder
{
    public function run(): void
    {
        $wards = [
            "DS-KIN" => [
                ["name" => "Mwananyamala", "code" => "DS-KIN-MWA"],
                ["name" => "Kigogo", "code" => "DS-KIN-KIG"],
                ["name" => "Msasani", "code" => "DS-KIN-MSA"],
                ["name" => "Mikocheni", "code" => "DS-KIN-MIK"],
                ["name" => "Kinondoni", "code" => "DS-KIN-KIN"],
                ["name" => "Magomeni", "code" => "DS-KIN-MAG"],
                ["name" => "Kunduchi", "code" => "DS-KIN-KUN"],
                ["name" => "Bunju", "code" => "DS-KIN-BUN"],
            ],
            "DS-ILA" => [
                ["name" => "Kariakoo", "code" => "DS-ILA-KAR"],
                ["name" => "Upanga Mashariki", "code" => "DS-ILA-UPM"],
                ["name" => "Upanga Magharibi", "code" => "DS-ILA-UPG"],
                ["name" => "Kisutu", "code" => "DS-ILA-KIS"],
                ["name" => "Mchikichini", "code" => "DS-ILA-MCH"],
                ["name" => "Ilala", "code" => "DS-ILA-ILA"],
                ["name" => "Buguruni", "code" => "DS-ILA-BUG"],
                ["name" => "Tabata", "code" => "DS-ILA-TAB"],
            ],
            "AR-ARM" => [
                ["name" => "Sekei", "code" => "AR-ARM-SEK"],
                ["name" => "Themi", "code" => "AR-ARM-THE"],
                ["name" => "Kaloleni", "code" => "AR-ARM-KAL"],
                ["name" => "Kati", "code" => "AR-ARM-KAT"],
                ["name" => "Lemara", "code" => "AR-ARM-LEM"],
                ["name" => "Daraja II", "code" => "AR-ARM-DAR"],
                ["name" => "Engutoto", "code" => "AR-ARM-ENG"],
                ["name" => "Elerai", "code" => "AR-ARM-ELE"],
            ],
            "DD-DOM" => [
                ["name" => "Kikuyu", "code" => "DD-DOM-KIK"],
                ["name" => "Uhuru", "code" => "DD-DOM-UHU"],
                ["name" => "Chamwino", "code" => "DD-DOM-CHA"],
                ["name" => "Kizota", "code" => "DD-DOM-KIZ"],
                ["name" => "Nkuhungu", "code" => "DD-DOM-NKU"],
                ["name" => "Mnadani", "code" => "DD-DOM-MNA"],
                ["name" => "Majengo", "code" => "DD-DOM-MAJ"],
                ["name" => "Madukani", "code" => "DD-DOM-MAD"],
            ],
            "IR-IRM" => [
                ["name" => "Mwangata", "code" => "IR-IRM-MWA"],
                ["name" => "Kihesa", "code" => "IR-IRM-KIH"],
                ["name" => "Mtwivila", "code" => "IR-IRM-MTW"],
                ["name" => "Ruaha", "code" => "IR-IRM-RUA"],
                ["name" => "Kwakilosa", "code" => "IR-IRM-KWA"],
                ["name" => "Gangilonga", "code" => "IR-IRM-GAN"],
                ["name" => "Mshindo", "code" => "IR-IRM-MSH"],
                ["name" => "Kitwiru", "code" => "IR-IRM-KIT"],
            ],
        ];

        $count = 0;
        foreach ($wards as $districtCode => $districtWards) {
            $district = District::where("code", $districtCode)->first();
            if (!$district) {
                continue;
            }

            foreach ($districtWards as $ward) {
                Ward::updateOrCreate(
                    ["code" => $ward["code"]],
                    [
                        "district_id" => $district->id,
                        "name" => $ward["name"],
                        "code" => $ward["code"],
                        "status" => "active",
                    ]
                );
                $count++;
            }
        }

        $this->command->info("Wards seeded: $count");
    }
}
