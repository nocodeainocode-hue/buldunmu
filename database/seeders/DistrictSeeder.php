<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districtsByCity = [
            'istanbul' => ['Kadıköy', 'Beşiktaş', 'Şişli', 'Üsküdar', 'Fatih', 'Bakırköy'],
            'ankara' => ['Çankaya', 'Keçiören', 'Yenimahalle', 'Mamak'],
            'izmir' => ['Karşıyaka', 'Bornova', 'Konak', 'Buca'],
            'bursa' => ['Nilüfer', 'Osmangazi'],
            'antalya' => ['Muratpaşa', 'Konyaaltı', 'Alanya'],
        ];

        foreach ($districtsByCity as $citySlug => $districtNames) {
            $city = City::withoutGlobalScope('directory')
                ->where('slug', $citySlug)
                ->whereNull('directory_id')
                ->first();

            if (!$city) {
                continue;
            }

            foreach ($districtNames as $name) {
                District::firstOrCreate(
                    [
                        'city_id' => $city->id,
                        'slug' => Str::slug($name),
                    ],
                    [
                        'name' => $name,
                        'directory_id' => null,
                    ],
                );
            }
        }
    }
}
