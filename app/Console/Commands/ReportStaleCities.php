<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Console\Command;

class ReportStaleCities extends Command
{
    protected $signature = 'cities:report-stale {--prune : Delete stale tenant copies with 0 companies}';
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

        if ($this->option('prune')) {
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
}
