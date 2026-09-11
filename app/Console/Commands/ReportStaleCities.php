<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Company;
use App\Models\District;
use App\Models\ListingRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReportStaleCities extends Command
{
    protected $signature = 'cities:report-stale
        {--prune : Delete stale tenant copies with 0 companies}
        {--merge : Move related records to canonical cities and delete stale copies}';

    protected $description = 'Report (and optionally prune) stale tenant-specific city copies';

    public function handle(): int
    {
        $canonicalSlugs = City::withoutGlobalScope('directory')
            ->whereNull('directory_id')
            ->pluck('slug')
            ->toArray();

        $staleCopies = City::withoutGlobalScope('directory')
            ->whereNotNull('directory_id')
            ->whereIn('slug', $canonicalSlugs)
            ->with('directory')
            ->get();

        if ($staleCopies->isEmpty()) {
            $this->info('✅ Temizlenecek eski şehir kopyası bulunamadı.');

            return self::SUCCESS;
        }

        $grouped = $staleCopies->groupBy('slug');
        $tableRows = [];
        $prunableIds = [];

        foreach ($grouped as $slug => $cities) {
            foreach ($cities as $city) {
                $companyCount = Company::withoutGlobalScope('directory')
                    ->where('city_id', $city->id)
                    ->count();

                $isPrunable = $companyCount === 0;
                if ($isPrunable) {
                    $prunableIds[] = $city->id;
                }

                $tableRows[] = [
                    $city->id,
                    $city->name,
                    $slug,
                    $city->directory->domain ?? '—',
                    $city->directory_id,
                    $companyCount,
                    $isPrunable ? 'EVET' : 'HAYIR (firma var)',
                ];
            }
        }

        $this->newLine();
        $this->info(sprintf(
            '🔍 %d eski şehir kopyası bulundu (%d benzersiz slug).',
            count($tableRows),
            $grouped->count(),
        ));
        $this->newLine();

        $this->table(
            ['ID', 'Ad', 'Slug', 'Domain', 'Dir ID', 'Firma', 'Silinebilir'],
            $tableRows,
        );

        $this->newLine();
        $this->info(sprintf(
            '🗑️  Silinebilir: %d / %d adet (sıfır firmaya bağlı).',
            count($prunableIds),
            count($tableRows),
        ));

        if ($this->option('merge')) {
            $movedCompanies = 0;

            foreach ($staleCopies as $staleCity) {
                $movedCompanies += $this->mergeIntoCanonicalCity($staleCity);
            }

            $this->newLine();
            $this->info("✅ {$movedCompanies} firma ortak şehir kayıtlarına taşındı; eski şehir kopyaları silindi.");
        } elseif ($this->option('prune')) {
            if (empty($prunableIds)) {
                $this->warn('Hiçbir kopya silinemez durumda değil.');

                return self::SUCCESS;
            }

            $deleted = City::withoutGlobalScope('directory')
                ->whereIn('id', $prunableIds)
                ->delete();

            $this->newLine();
            $this->info("✅ {$deleted} eski şehir kopyası silindi.");
        } else {
            $this->newLine();
            $this->comment('Kopyaları silmek için --prune bayrağıyla tekrar çalıştırın.');
        }

        return self::SUCCESS;
    }

    private function mergeIntoCanonicalCity(City $staleCity): int
    {
        $canonicalCity = City::withoutGlobalScope('directory')
            ->whereNull('directory_id')
            ->where('slug', $staleCity->slug)
            ->firstOrFail();

        return DB::transaction(function () use ($staleCity, $canonicalCity): int {
            $staleDistricts = District::withoutGlobalScope('directory')
                ->where('city_id', $staleCity->id)
                ->get();

            foreach ($staleDistricts as $staleDistrict) {
                $canonicalDistrict = District::withoutGlobalScope('directory')
                    ->where('city_id', $canonicalCity->id)
                    ->where('slug', $staleDistrict->slug)
                    ->first();

                if ($canonicalDistrict) {
                    Company::withoutGlobalScope('directory')
                        ->where('district_id', $staleDistrict->id)
                        ->update(['district_id' => $canonicalDistrict->id]);
                    ListingRequest::withoutGlobalScope('directory')
                        ->where('district_id', $staleDistrict->id)
                        ->update(['district_id' => $canonicalDistrict->id]);
                    $staleDistrict->delete();
                } else {
                    $staleDistrict->update(['city_id' => $canonicalCity->id]);
                }
            }

            $companyQuery = Company::withoutGlobalScope('directory')->where('city_id', $staleCity->id);
            $movedCompanies = $companyQuery->count();
            $companyQuery->update(['city_id' => $canonicalCity->id]);

            ListingRequest::withoutGlobalScope('directory')
                ->where('city_id', $staleCity->id)
                ->update(['city_id' => $canonicalCity->id]);

            $staleCity->delete();

            return $movedCompanies;
        });
    }
}
