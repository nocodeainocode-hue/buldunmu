<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\District;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class SyncTurkeyDistricts extends Command
{
    protected $signature = 'catalog:sync-districts';

    protected $description = 'Ortak Türkiye ilçe kataloğunu sabitlenmiş veri kaynağından günceller';

    private const SOURCE_URL = 'https://raw.githubusercontent.com/open-admin-data/turkey-administrative-divisions/6dfe36b4360c4395656edc2ea0730224d269bc86/data/all-district.json';

    public function handle(): int
    {
        $records = Http::retry(3, 500)
            ->timeout(30)
            ->get(self::SOURCE_URL)
            ->throw()
            ->json();

        if (! is_array($records) || $records === []) {
            throw new RuntimeException('İlçe veri kaynağı geçerli bir liste döndürmedi.');
        }

        $cities = City::withoutGlobalScope('directory')
            ->whereNull('directory_id')
            ->get()
            ->keyBy('slug');

        $synced = 0;
        $skipped = 0;

        DB::transaction(function () use ($records, $cities, &$synced, &$skipped): void {
            foreach ($records as $record) {
                $cityName = data_get($record, 'parent.name.local');
                $districtName = data_get($record, 'name.local');
                $city = is_string($cityName) ? $cities->get(Str::slug($cityName)) : null;

                if (! $city || ! is_string($districtName) || blank($districtName)) {
                    $skipped++;

                    continue;
                }

                District::withoutGlobalScope('directory')->updateOrCreate(
                    [
                        'city_id' => $city->id,
                        'slug' => Str::slug($districtName),
                        'directory_id' => null,
                    ],
                    ['name' => $districtName],
                );

                $synced++;
            }
        });

        $this->info("{$synced} ilçe ortak kataloğa eklendi veya güncellendi.");

        if ($skipped > 0) {
            $this->warn("{$skipped} kayıt eşleşen ortak şehir bulunamadığı için atlandı.");
        }

        return self::SUCCESS;
    }
}
