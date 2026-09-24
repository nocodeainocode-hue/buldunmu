<?php

namespace App\Console\Commands;

use App\Models\Directory;
use App\View\Helpers\ThemeHelper;
use Illuminate\Console\Command;

class ApplyDirectoryThemePlan extends Command
{
    protected $signature = 'directories:apply-theme-plan {--apply : Save the listed theme assignments}';

    protected $description = 'Preview or apply the domain-based theme plan for active directories';

    public function handle(): int
    {
        $plan = config('directory_themes', []);
        $invalid = array_filter($plan, fn (string $theme) => ! array_key_exists($theme, ThemeHelper::TEMPLATES));

        if ($invalid) {
            $this->error('Bilinmeyen tema anahtarı: '.implode(', ', array_unique($invalid)));

            return self::FAILURE;
        }

        $directories = Directory::query()->where('status', 'active')->get()->keyBy('domain');
        $missing = array_values(array_diff(array_keys($plan), $directories->keys()->all()));
        if ($missing && $this->option('apply')) {
            $this->error('Eksik aktif rehberler nedeniyle hiçbir tema kaydedilmedi: '.implode(', ', $missing));

            return self::FAILURE;
        }
        $rows = [];
        $changed = 0;

        foreach ($plan as $domain => $theme) {
            $directory = $directories->get($domain);
            if (! $directory) {
                continue;
            }

            $rows[] = [$domain, $directory->template, $theme, $directory->template === $theme ? 'Aynı' : 'Değişecek'];
            if ($directory->template !== $theme && $this->option('apply')) {
                $directory->update(['template' => $theme]);
                $changed++;
            }
        }

        $this->table(['Alan adı', 'Mevcut tema', 'Önerilen tema', 'Durum'], $rows);
        if ($missing) {
            $this->warn('Aktif kaydı bulunamayan alan adları: '.implode(', ', $missing));
        }
        $this->info($this->option('apply') ? "$changed rehber güncellendi." : 'Ön izleme. Kaydetmek için --apply kullanın.');

        return $missing ? self::FAILURE : self::SUCCESS;
    }
}
