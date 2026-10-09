<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Directory;
use App\Services\CompanyDescriptionGenerator;
use Illuminate\Console\Command;

class FillCompanyDescriptions extends Command
{
    protected $signature = 'companies:fill-descriptions
        {--batch= : Yalnızca bu içe aktarma batch\'indeki firmalar}
        {--directory= : Yalnızca bu rehberin (id veya domain) firmaları}
        {--limit=0 : En fazla kaç firma (0 = hepsi)}
        {--force : Dolu açıklamaların da üzerine yaz}
        {--dry-run : Yazmadan örnek göster}';

    protected $description = 'Açıklaması boş firmalara, firmanın gerçek verilerinden rehbere özgü kısa açıklama ve metin üretir.';

    public function handle(CompanyDescriptionGenerator $generator): int
    {
        $directories = Directory::all()->keyBy('id');

        $query = Company::withoutGlobalScope('directory')
            ->with(['category', 'city', 'district'])
            ->whereNotNull('directory_id')
            ->when($this->option('batch'), fn ($q, $batch) => $q->where('import_batch_id', (int) $batch))
            ->when($this->option('directory'), function ($q, $value) use ($directories) {
                $id = is_numeric($value) ? (int) $value : $directories->firstWhere('domain', $value)?->id;

                return $q->where('directory_id', $id ?? 0);
            })
            ->unless($this->option('force'), fn ($q) => $q
                ->where(fn ($w) => $w->whereNull('short_description')->orWhere('short_description', ''))
                ->where(fn ($w) => $w->whereNull('description')->orWhere('description', '')));

        $limit = (int) $this->option('limit');
        $total = (clone $query)->count();
        $target = $limit > 0 ? min($limit, $total) : $total;

        $this->info("Hedef: {$target} firma".($this->option('dry-run') ? ' (dry-run, yazılmayacak)' : '').'.');

        $done = 0;
        $query->orderBy('id')->chunkById(500, function ($companies) use ($generator, $directories, $limit, &$done) {
            foreach ($companies as $company) {
                if ($limit > 0 && $done >= $limit) {
                    return false;
                }

                $directory = $directories->get($company->directory_id);
                if (! $directory) {
                    continue;
                }

                $text = $generator->generate($company, $directory);

                if ($this->option('dry-run')) {
                    if ($done < 3) {
                        $this->newLine();
                        $this->line("<info>{$directory->domain}</info> · {$company->name}");
                        $this->line('  Kısa: '.$text['short']);
                        $this->line('  '.str_replace("\n", "\n  ", strip_tags(str_replace('</p>', "\n", $text['html']))));
                    }
                } else {
                    // Model olaylarını (slug/logo) tetiklemeden toplu ve hızlı yaz.
                    Company::withoutGlobalScope('directory')->whereKey($company->id)->update([
                        'short_description' => $text['short'],
                        'description' => $text['html'],
                    ]);
                }

                $done++;
            }
        });

        $this->newLine();
        $this->info("{$done} firma ".($this->option('dry-run') ? 'için örnek üretildi.' : 'güncellendi.'));

        return self::SUCCESS;
    }
}
