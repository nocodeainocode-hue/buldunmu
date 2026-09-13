<?php

namespace App\Console\Commands;

use App\Models\Directory;
use App\Support\PageContent;
use Illuminate\Console\Command;

class RepairDirectoryPageContents extends Command
{
    protected $signature = 'directories:repair-page-contents
        {--dry-run : Sadece bulunacak eski içerikleri raporla}';

    protected $description = 'Reset legacy generated page content and remove empty rich-editor page content';

    public function handle(): int
    {
        $changedDirectories = 0;
        $legacyPagesReset = 0;
        $emptyContentsRemoved = 0;

        Directory::query()->orderBy('id')->each(function (Directory $directory) use (&$changedDirectories, &$legacyPagesReset, &$emptyContentsRemoved): void {
            $contents = $directory->page_contents ?? [];
            $changed = false;

            foreach (['about', 'contact', 'privacy', 'terms'] as $key) {
                $content = $contents[$key] ?? null;

                if (! is_string($content)) {
                    continue;
                }

                if (! PageContent::isMeaningful($content)) {
                    unset($contents[$key]);
                    $emptyContentsRemoved++;
                    $changed = true;

                    continue;
                }

                if (str_contains($content, 'İşletme Bulvarı')) {
                    unset($contents[$key]);
                    $legacyPagesReset++;
                    $changed = true;
                }
            }

            if (! $changed) {
                return;
            }

            $changedDirectories++;
            $this->line("- {$directory->domain} ({$directory->name})");

            if (! $this->option('dry-run')) {
                $directory->update(['page_contents' => $contents]);
            }
        });

        $mode = $this->option('dry-run') ? 'Ön izleme' : 'Düzeltme';
        $this->info("{$mode} tamamlandı: {$changedDirectories} rehber, {$legacyPagesReset} eski şablon sayfası, {$emptyContentsRemoved} boş sayfa içeriği.");

        if ($this->option('dry-run') && $changedDirectories > 0) {
            $this->comment('Uygulamak için --dry-run olmadan komutu yeniden çalıştırın.');
        }

        return self::SUCCESS;
    }
}
