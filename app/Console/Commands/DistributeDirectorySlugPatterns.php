<?php

namespace App\Console\Commands;

use App\Models\Directory;
use Illuminate\Console\Command;

class DistributeDirectorySlugPatterns extends Command
{
    protected $signature = 'directories:distribute-slug-patterns
                            {--apply : Desenleri veritabanına kaydet}
                            {--all : Pasif rehberleri de dahil et}';

    protected $description = 'Aktif rehberlerde firma URL desenini tutarlı biçimde {name}-{city} olarak ayarlar';

    public function handle(): int
    {
        $directories = Directory::query()
            ->when(!$this->option('all'), fn($query) => $query->where('status', 'active'))
            ->orderBy('id')->get();

        if ($directories->isEmpty()) {
            $this->warn('Dağıtılacak rehber bulunamadı.');
            return self::SUCCESS;
        }

        $rows = [];
        foreach ($directories as $directory) {
            $pattern = '{name}-{city}';
            $rows[] = [$directory->id, $directory->name, $directory->domain, $directory->slug_pattern, $pattern];
            if ($this->option('apply')) {
                $directory->update(['slug_pattern' => $pattern]);
            }
        }

        $this->table(['ID', 'Rehber', 'Domain', 'Mevcut', 'Önerilen'], $rows);
        $this->newLine();
        $this->info($this->option('apply')
            ? $directories->count() . ' rehber güncellendi. Mevcut firma slugları değiştirilmedi.'
            : 'Bu yalnızca ön izlemedir. Uygulamak için --apply kullanın.');

        return self::SUCCESS;
    }
}
