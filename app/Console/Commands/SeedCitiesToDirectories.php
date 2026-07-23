<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Support\TurkeyCities;
use Illuminate\Console\Command;

class SeedCitiesToDirectories extends Command
{
    protected $signature = 'seed:cities';
    protected $description = 'Seed the canonical 81 Turkish cities shared by all directories';

    public function handle(): int
    {
        $count = 0;

        foreach (TurkeyCities::options() as $slug => $name) {
            $city = City::firstOrCreate(
                ['slug' => $slug, 'directory_id' => null],
                ['name' => $name],
            );

            if ($city->wasRecentlyCreated) {
                $count++;
            }
        }

        $this->info("{$count} canonical şehir eklendi; tüm rehberler ortak 81 il kataloğunu kullanır.");

        return self::SUCCESS;
    }
}
