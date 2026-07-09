<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            // İstanbul (1)
            ['city_id' => 1, 'name' => 'Kadıköy'], ['city_id' => 1, 'name' => 'Beşiktaş'],
            ['city_id' => 1, 'name' => 'Şişli'], ['city_id' => 1, 'name' => 'Üsküdar'],
            ['city_id' => 1, 'name' => 'Fatih'], ['city_id' => 1, 'name' => 'Bakırköy'],
            // Ankara (2)
            ['city_id' => 2, 'name' => 'Çankaya'], ['city_id' => 2, 'name' => 'Keçiören'],
            ['city_id' => 2, 'name' => 'Yenimahalle'], ['city_id' => 2, 'name' => 'Mamak'],
            // İzmir (3)
            ['city_id' => 3, 'name' => 'Karşıyaka'], ['city_id' => 3, 'name' => 'Bornova'],
            ['city_id' => 3, 'name' => 'Konak'], ['city_id' => 3, 'name' => 'Buca'],
            // Bursa (4)
            ['city_id' => 4, 'name' => 'Nilüfer'], ['city_id' => 4, 'name' => 'Osmangazi'],
            // Antalya (5)
            ['city_id' => 5, 'name' => 'Muratpaşa'], ['city_id' => 5, 'name' => 'Konyaaltı'],
            ['city_id' => 5, 'name' => 'Alanya'],
        ];

        foreach ($districts as $d) {
            \App\Models\District::create([
                'city_id' => $d['city_id'],
                'name' => $d['name'],
                'slug' => Str::slug($d['name']),
            ]);
        }
    }
}