<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Directory;
use App\Services\CompanyDescriptionGenerator;
use App\Services\CompanySlugService;
use Illuminate\Console\Command;

class RepairCompanyEntities extends Command
{
    protected $signature = 'companies:repair-entities
        {--batch= : Yalnızca bu içe aktarma batch\'indeki firmalar}
        {--dry-run : Yazmadan neyin değişeceğini göster}';

    protected $description = 'Firma adı/adresindeki HTML varlıklarını (&#8211; gibi) çözer; slug içindeki kalıntıyı (8211) düzeltir ve bu adla üretilmiş açıklamaları yeniler.';

    private const ENTITY = '/&(#\d+|#x[0-9a-f]+|[a-z][a-z0-9]+);/i';

    public function handle(CompanySlugService $slugs, CompanyDescriptionGenerator $generator): int
    {
        $dry = (bool) $this->option('dry-run');
        $directories = Directory::all()->keyBy('id');
        $fixed = 0;
        $slugChanges = 0;
        $regenerated = 0;
        $samples = [];

        Company::withoutGlobalScope('directory')
            ->with(['category', 'city', 'district'])
            ->when($this->option('batch'), fn ($q, $batch) => $q->where('import_batch_id', (int) $batch))
            ->where(fn ($q) => $q->where('name', 'like', '%&%;%')->orWhere('address', 'like', '%&%;%'))
            ->orderBy('id')
            ->chunkById(500, function ($companies) use ($slugs, $generator, $directories, $dry, &$fixed, &$slugChanges, &$regenerated, &$samples) {
                foreach ($companies as $company) {
                    $oldName = (string) $company->name;
                    $newName = $this->decode($oldName);
                    $oldAddress = (string) $company->address;
                    $newAddress = $this->decode($oldAddress);

                    if ($newName === $oldName && $newAddress === $oldAddress) {
                        continue;
                    }

                    $fixed++;
                    $changes = ['name' => $newName, 'address' => $newAddress !== '' ? $newAddress : null];
                    $oldSlug = (string) $company->slug;

                    if ($newName !== $oldName) {
                        // Slug yalnızca ad değiştiyse yeniden üretilir ("...-8211-..." kalıntısı gider).
                        $company->name = $newName;
                        $newSlug = $slugs->generate($company);

                        if ($newSlug !== $oldSlug) {
                            $changes['slug'] = $newSlug;
                            $slugChanges++;
                        }

                        // Bu adla üretilmiş açıklamalar yeniden yazılır; elle yazılanlara dokunulmaz.
                        $generated = (filled($company->short_description) && str_contains($company->short_description, $oldName))
                            || (filled($company->description) && str_contains($company->description, e($oldName)));

                        if ($generated && ($directory = $directories->get($company->directory_id))) {
                            $text = $generator->generate($company, $directory);
                            $changes['short_description'] = $text['short'];
                            $changes['description'] = $text['html'];
                            $regenerated++;
                        }
                    }

                    if (count($samples) < 5) {
                        $samples[] = [$oldName, $newName, $oldSlug, $changes['slug'] ?? '(aynı)'];
                    }

                    if (! $dry) {
                        $company->allowSlugChange()->forceFill($changes)->save();
                    }
                }
            });

        if ($samples !== []) {
            $this->table(['Eski ad', 'Yeni ad', 'Eski slug', 'Yeni slug'], $samples);
        }

        $this->info(($dry ? '[dry-run] ' : '')."{$fixed} firma düzeltildi, {$slugChanges} slug yenilendi, {$regenerated} açıklama yeniden üretildi.");

        return self::SUCCESS;
    }

    private function decode(string $value): string
    {
        for ($i = 0; $i < 3 && preg_match(self::ENTITY, $value); $i++) {
            $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return trim($value);
    }
}
