<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            CitySeeder::class,
            DistrictSeeder::class,
            SiteSettingSeeder::class,
            CompanySeeder::class,
        ]);
    }
}
