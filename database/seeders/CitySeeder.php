<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name' => 'İstanbul', 'plate_code' => '34'],
            ['name' => 'Ankara', 'plate_code' => '06'],
            ['name' => 'İzmir', 'plate_code' => '35'],
            ['name' => 'Bursa', 'plate_code' => '16'],
            ['name' => 'Antalya', 'plate_code' => '07'],
            ['name' => 'Adana', 'plate_code' => '01'],
            ['name' => 'Konya', 'plate_code' => '42'],
            ['name' => 'Gaziantep', 'plate_code' => '27'],
            ['name' => 'Mersin', 'plate_code' => '33'],
            ['name' => 'Kayseri', 'plate_code' => '38'],
            ['name' => 'Eskişehir', 'plate_code' => '26'],
            ['name' => 'Samsun', 'plate_code' => '55'],
            ['name' => 'Trabzon', 'plate_code' => '61'],
            ['name' => 'Diyarbakır', 'plate_code' => '21'],
            ['name' => 'Kocaeli', 'plate_code' => '41'],
        ];

        foreach ($cities as $city) {
            \App\Models\City::create([
                'name' => $city['name'],
                'slug' => Str::slug($city['name']),
                'plate_code' => $city['plate_code'],
            ]);
        }
    }
}