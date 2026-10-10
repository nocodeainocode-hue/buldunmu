<?php

namespace App\Console\Commands;

use App\Models\District;
use App\Support\DistrictNameFixes;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FixDistrictNames extends Command
{
    protected $signature = 'catalog:fix-district-names {--dry-run : Yalnızca değişecek adları listele}';

    protected $description = 'İlçe adlarındaki ı/i yazım hatalarını (Sarioğlan, Hacilar, Akkişla…) ve bozuk birleşik nokta işaretlerini düzeltir';

    public function handle(): int
    {
        $changed = 0;
        $skipped = 0;
        $sample = [];

        District::withoutGlobalScopes()->with(['city' => fn ($query) => $query->withoutGlobalScopes()])->orderBy('id')->chunkById(500, function ($districts) use (&$changed, &$skipped, &$sample): void {
            foreach ($districts as $district) {
                if (! $district->city) {
                    continue;
                }

                $fixed = DistrictNameFixes::fix($district->city, (string) $district->name);

                if ($fixed === $district->name) {
                    continue;
                }

                // Adres (slug) değişmemeli: yalnızca yazım düzelir, bağlantılar bozulmaz.
                if (Str::slug($fixed) !== Str::slug($district->name)) {
                    $skipped++;

                    continue;
                }

                if (count($sample) < 12) {
                    $sample[] = [$district->city->name, $district->name, $fixed];
                }

                if (! $this->option('dry-run')) {
                    $district->forceFill(['name' => $fixed])->saveQuietly();
                }

                $changed++;
            }
        });

        $this->table(['Şehir', 'Eski ad', 'Yeni ad'], $sample);
        $this->info(($this->option('dry-run') ? "{$changed} ilçe adı düzeltilecek (hiçbir şey değiştirilmedi)." : "{$changed} ilçe adı düzeltildi.")
            .($skipped ? " {$skipped} kayıt adresi değişeceği için atlandı." : ''));

        return self::SUCCESS;
    }
}
