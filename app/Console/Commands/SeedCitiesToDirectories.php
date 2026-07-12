<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Directory;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeedCitiesToDirectories extends Command
{
    protected $signature = 'seed:cities';
    protected $description = 'Seed 81 Turkish cities to all directories';

    public function handle(): int
    {
        $cities = [
            'Adana', 'Adıyaman', 'Afyonkarahisar', 'Ağrı', 'Amasya', 'Ankara', 'Antalya', 'Artvin',
            'Aydın', 'Balıkesir', 'Bilecik', 'Bingöl', 'Bitlis', 'Bolu', 'Burdur', 'Bursa',
            'Çanakkale', 'Çankırı', 'Çorum', 'Denizli', 'Diyarbakır', 'Edirne', 'Elazığ', 'Erzincan',
            'Erzurum', 'Eskişehir', 'Gaziantep', 'Giresun', 'Gümüşhane', 'Hakkari', 'Hatay', 'Isparta',
            'Mersin', 'İstanbul', 'İzmir', 'Kars', 'Kastamonu', 'Kayseri', 'Kırklareli', 'Kırşehir',
            'Kocaeli', 'Konya', 'Kütahya', 'Malatya', 'Manisa', 'Kahramanmaraş', 'Mardin', 'Muğla',
            'Muş', 'Nevşehir', 'Niğde', 'Ordu', 'Rize', 'Sakarya', 'Samsun', 'Siirt', 'Sinop',
            'Sivas', 'Tekirdağ', 'Tokat', 'Trabzon', 'Tunceli', 'Şanlıurfa', 'Uşak', 'Van',
            'Yozgat', 'Zonguldak', 'Aksaray', 'Bayburt', 'Karaman', 'Kırıkkale', 'Batman', 'Şırnak',
            'Bartın', 'Ardahan', 'Iğdır', 'Yalova', 'Karabük', 'Kilis', 'Osmaniye', 'Düzce',
        ];

        $directories = Directory::where('status', 'active')->get();
        $count = 0;

        foreach ($directories as $dir) {
            $visibleSlugs = $dir->visibleCitySlugs();
            foreach ($cities as $cityName) {
                $slug = Str::slug($cityName);
                if ($dir->geography_mode !== 'national' && !in_array($slug, $visibleSlugs, true)) {
                    continue;
                }
                if (!City::where('directory_id', $dir->id)->where('slug', $slug)->exists()) {
                    City::create([
                        'name' => $cityName,
                        'slug' => $slug,
                        'directory_id' => $dir->id,
                    ]);
                    $count++;
                }
            }
        }

        $this->info("{$count} şehir eklendi (" . $directories->count() . " rehbere).");

        return self::SUCCESS;
    }
}
