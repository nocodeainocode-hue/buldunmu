<?php

namespace App\Console\Commands;

use App\Models\Directory;
use App\View\Helpers\ThemeHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OnboardDirectoryBatch extends Command
{
    protected $signature = 'directories:onboard-batch {--apply : Create missing directories as passive}';

    protected $description = 'Preview or create the October 2026 domain batch in the shared admin panel';

    public function handle(): int
    {
        try {
            $batch = $this->readBatch();
        } catch (RuntimeException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $existing = Directory::query()->whereIn('domain', array_keys($batch))->get()->keyBy('domain');
        $slugs = Directory::query()->whereIn('slug', array_column($batch, 'slug'))->get()->keyBy('slug');
        $errors = [];
        $rows = [];
        foreach ($batch as $domain => $data) {
            $directory = $existing->get($domain);
            if (! $directory && $slugs->has($data['slug'])) {
                $errors[] = "$domain: slug {$data['slug']} başka bir rehberde kullanılıyor.";
            }
            $rows[] = [$domain, $data['name'], $data['template'], $directory ? 'Mevcut, korunacak' : 'Pasif oluşturulacak'];
        }
        $this->table(['Domain', 'Ad', 'Tema', 'İşlem'], $rows);
        if ($errors) {
            foreach ($errors as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }
        if (! $this->option('apply')) {
            $this->info('Ön izleme. Kayıt oluşturmak için --apply kullanın.');

            return self::SUCCESS;
        }

        $created = DB::transaction(function () use ($batch, $existing): int {
            $count = 0;
            foreach ($batch as $domain => $data) {
                if ($existing->has($domain)) {
                    continue;
                }
                Directory::create([
                    'domain' => $domain,
                    'name' => $data['name'],
                    'slug' => $data['slug'],
                    'template' => $data['template'],
                    'status' => 'passive',
                    'geography_mode' => 'national',
                    'blog_layout' => 'editorial',
                    'meta_title' => $data['name'].' | Firma Rehberi',
                    'meta_description' => $data['name'].' üzerinde yerel firmaları ve hizmetleri keşfedin.',
                ]);
                $count++;
            }

            return $count;
        });
        $this->info("$created pasif rehber oluşturuldu; mevcut kayıtlar değiştirilmedi.");

        return self::SUCCESS;
    }

    private function readBatch(): array
    {
        $path = base_path('ops/directory-batch-2026-10.csv');
        $handle = fopen($path, 'r');
        if (! $handle) {
            throw new RuntimeException("Batch dosyası açılamadı: $path");
        }
        try {
            $header = fgetcsv($handle);
            if ($header !== ['domain', 'name', 'template']) {
                throw new RuntimeException('Batch CSV başlığı domain,name,template olmalıdır.');
            }
            $batch = [];
            $seenSlugs = [];
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) !== 3) {
                    throw new RuntimeException('Batch CSV satırında tam üç alan olmalıdır.');
                }
                [$domain, $name, $template] = array_map('trim', $row);
                $slug = explode('.', $domain, 2)[0];
                if (! preg_match('/^[a-z0-9-]+\.com\.tr$/', $domain) || $name === '' || ! isset(ThemeHelper::TEMPLATES[$template])) {
                    throw new RuntimeException("Geçersiz domain, ad veya tema: $domain");
                }
                if (isset($batch[$domain]) || isset($seenSlugs[$slug])) {
                    throw new RuntimeException("Tekrarlanan domain veya slug: $domain");
                }
                $seenSlugs[$slug] = true;
                $batch[$domain] = compact('name', 'template', 'slug');
            }
        } finally {
            fclose($handle);
        }

        if (count($batch) !== 43) {
            throw new RuntimeException('Batch tam 43 yeni rehber içermelidir.');
        }

        return $batch;
    }
}
