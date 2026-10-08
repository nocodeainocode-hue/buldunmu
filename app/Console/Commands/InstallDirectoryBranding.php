<?php

namespace App\Console\Commands;

use App\Models\Directory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class InstallDirectoryBranding extends Command
{
    protected $signature = 'directories:install-branding
        {--apply : Copy the reviewed assets and update directory records}
        {--replace-existing : Replace existing logos and favicons, saving their paths in a backup}';

    protected $description = 'Preview or install the 83 directory logos, favicons and home-screen icons';

    public function handle(): int
    {
        try {
            $root = resource_path('branding/directories');
            $brands = json_decode(file_get_contents($root.'/manifest.json'), true, 512, JSON_THROW_ON_ERROR);
            if (count($brands) !== 83 || count(array_unique(array_column($brands, 'domain'))) !== 83) {
                throw new RuntimeException('Marka listesi 83 farklı domain içermeli.');
            }
            $directories = Directory::whereIn('domain', array_column($brands, 'domain'))->get()->keyBy('domain');
            $changes = [];
            $files = ['logo.svg', 'logo-dark.svg', 'favicon.svg', 'favicon.png', 'icon-180.png', 'icon-192.png', 'icon-512.png'];
            foreach ($brands as $brand) {
                $domain = $brand['domain'];
                $directory = $directories->get($domain);
                if (! $directory) {
                    throw new RuntimeException("Rehber bulunamadı: {$domain}. Hiçbir rehber güncellenmedi.");
                }
                $stem = $brand['directory'];
                if (! preg_match('/^[a-z0-9]+$/', $stem)) {
                    throw new RuntimeException('Marka klasörü geçersiz.');
                }
                foreach ($files as $file) {
                    if (! is_file("{$root}/{$stem}/{$file}")) {
                        throw new RuntimeException("Eksik marka dosyası: {$stem}/{$file}");
                    }
                }
                $base = "directories/branding/2026-10/{$stem}";
                $update = [];
                foreach (['logo' => 'logo.svg', 'favicon' => 'favicon.png'] as $field => $file) {
                    $path = "{$base}/{$file}";
                    if ($directory->$field !== $path && (blank($directory->$field) || $this->option('replace-existing'))) {
                        $update[$field] = $path;
                    }
                }
                if ($update) {
                    $changes[] = compact('directory', 'update');
                }
            }
            $this->info('83 marka dosyası doğrulandı; güncellenecek rehber: '.count($changes));
            if (! $this->option('apply')) {
                $this->info('Önizleme tamamlandı. Yüklemek için --apply; mevcut logoları da değiştirmek için --replace-existing kullanın.');

                return self::SUCCESS;
            }
            $backup = 'branding-backups/directories-'.now()->format('Ymd-His').'-'.bin2hex(random_bytes(3)).'.json';
            if ($changes && ! Storage::disk('local')->put($backup, json_encode(array_map(fn ($change) => [
                'id' => $change['directory']->id, 'domain' => $change['directory']->domain,
                'logo' => $change['directory']->logo, 'favicon' => $change['directory']->favicon,
            ], $changes), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR))) {
                throw new RuntimeException('Yedek kaydedilemedi; yükleme durduruldu.');
            }
            // Commit all files before updating pointers; a file error leaves DB records unchanged.
            foreach ($brands as $brand) {
                foreach ($files as $file) {
                    $destination = "directories/branding/2026-10/{$brand['directory']}/{$file}";
                    if (! Storage::disk('public')->put($destination, file_get_contents("{$root}/{$brand['directory']}/{$file}"))) {
                        throw new RuntimeException("Dosya yüklenemedi: {$destination}");
                    }
                }
            }
            DB::transaction(function () use ($changes): void {
                foreach ($changes as $change) {
                    $change['directory']->update($change['update']);
                }
            });
            $this->info(count($changes).' rehber güncellendi. 83 logo seti public diskine kopyalandı.');
            if ($changes) {
                $this->info('Önceki logo yollarının yedeği: '.Storage::disk('local')->path($backup));
            }

            return self::SUCCESS;
        } catch (\Throwable $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }
    }
}
