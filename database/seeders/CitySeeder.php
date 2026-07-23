<?php

namespace Database\Seeders;

use App\Models\City;
use App\Support\TurkeyCities;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        foreach (TurkeyCities::options() as $slug => $name) {
            City::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'directory_id' => null,
                ],
            );
        }
    }
}
