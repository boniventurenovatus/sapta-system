<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('=== LocationSeeder ===');

        // ============================================================
        // 1. REGIONS — Tanzania mikoa 31
        // ============================================================
        if (Schema::hasTable('regions')) {
            $regions = [
                'Arusha', 'Dar es Salaam', 'Dodoma', 'Geita', 'Iringa',
                'Kagera', 'Katavi', 'Kigoma', 'Kilimanjaro', 'Lindi',
                'Manyara', 'Mara', 'Mbeya', 'Morogoro', 'Mtwara',
                'Mwanza', 'Njombe', 'Pemba North', 'Pemba South', 'Pwani',
                'Rukwa', 'Ruvuma', 'Shinyanga', 'Simiyu', 'Singida',
                'Songwe', 'Tabora', 'Tanga', 'Unguja North', 'Unguja South',
                'Mjini Magharibi'
            ];

            foreach ($regions as $region) {
                DB::table('regions')->updateOrInsert(
                    ['name' => $region],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
            $this->command->info('✅ Regions (' . count($regions) . ') zimeundwa');
        }

        // ============================================================
        // 2. DISTRICTS — wilaya chache kwa kila mkoa
        // ============================================================
        if (Schema::hasTable('districts') && Schema::hasTable('regions')) {
            $districts = [
                'Dar es Salaam' => ['Kinondoni', 'Ilala', 'Temeke', 'Ubungo', 'Kigamboni'],
                'Arusha' => ['Arusha City', 'Arusha Rural', 'Karatu', 'Longido', 'Meru', 'Monduli', 'Ngorongoro'],
                'Mwanza' => ['Ilemela', 'Nyamagana', 'Sengerema', 'Geita', 'Misungwi'],
                'Dodoma' => ['Dodoma City', 'Bahi', 'Chamwino', 'Chemba', 'Kondoa', 'Kongwa', 'Mpwapwa'],
                'Mbeya' => ['Mbeya City', 'Mbeya Rural', 'Chunya', 'Kyela', 'Mbarali', 'Rungwe'],
                'Tanga' => ['Tanga City', 'Handeni', 'Kilindi', 'Korogwe', 'Lushoto', 'Muheza', 'Mkinga', 'Pangani'],
                'Morogoro' => ['Morogoro Urban', 'Morogoro Rural', 'Gairo', 'Kilombero', 'Kilosa', 'Mvomero', 'Ulanga', 'Malinyi', 'Ifakara'],
                'Iringa' => ['Iringa Urban', 'Iringa Rural', 'Kilolo', 'Mufindi'],
                'Kilimanjaro' => ['Moshi Urban', 'Moshi Rural', 'Hai', 'Rombo', 'Same', 'Siha', 'Mwanga'],
                'Ruvuma' => ['Songea Urban', 'Songea Rural', 'Mbinga', 'Namtumbo', 'Nyasa', 'Tunduru'],
            ];

            foreach ($districts as $regionName => $districtNames) {
                $region = DB::table('regions')->where('name', $regionName)->first();
                
                if ($region) {
                    foreach ($districtNames as $district) {
                        DB::table('districts')->updateOrInsert(
                            ['name' => $district, 'region_id' => $region->id],
                            ['created_at' => now(), 'updated_at' => now()]
                        );
                    }
                }
            }
            $this->command->info('✅ Districts zimeundwa');
        }

        // ============================================================
        // 3. WARDS — kata kwa kila wilaya
        // ============================================================
        if (Schema::hasTable('wards') && Schema::hasTable('districts')) {
            $wards = [
                'Kinondoni' => ['Mwananyamala', 'Mikocheni', 'Msasani', 'Kawe', 'Kunduchi', 'Makongo', 'Goba', 'Kigogo'],
                'Ilala' => ['Kariakoo', 'Kisutu', 'Upanga', 'Kivukoni', 'Ilala', 'Mchikichini'],
                'Temeke' => ['Mbagala', 'Tandika', 'Miburani', 'Kurasini', 'Chang\'ombe'],
                'Arusha City' => ['Kaloleni', 'Sekei', 'Themi', 'Ngarenaro', 'Elerai'],
                'Ilemela' => ['Kirumba', 'Bugogwa', 'Sangabuye', 'Buzuruga', 'Kitangiri'],
                'Nyamagana' => ['Mirongo', 'Isamilo', 'Mkuyuni', 'Pamba', 'Nyamagana'],
                'Dodoma City' => ['Chamwino', 'Kikuyu', 'Majengo', 'Uhuru', 'Viwandani'],
                'Mbeya City' => ['Uyole', 'Iyunga', 'Mwanjelwa', 'Sisimba', 'Forest'],
                'Tanga City' => ['Ngamiani', 'Makorora', 'Chumbageni', 'Pongwe', 'Mwanzange'],
                'Morogoro Urban' => ['Kichangani', 'Boma', 'Sabasaba', 'Mazimbu', 'Bishop'],
            ];

            foreach ($wards as $districtName => $wardNames) {
                $district = DB::table('districts')->where('name', $districtName)->first();
                
                if ($district) {
                    foreach ($wardNames as $ward) {
                        DB::table('wards')->updateOrInsert(
                            ['name' => $ward, 'district_id' => $district->id],
                            ['created_at' => now(), 'updated_at' => now()]
                        );
                    }
                }
            }
            $this->command->info('✅ Wards zimeundwa');
        }

        $this->command->info('✅ LocationSeeder imekamilika!');
    }
}