<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\Post;
use Illuminate\Console\Command;

class CheckSeoReadiness extends Command
{
    protected $signature = 'seo:check
        {--directory= : Only check one directory by domain or slug}';

    protected $description = 'Sitemap ve indexlenebilirlik açısından rehberleri salt okunur denetler';

    public function handle(): int
    {
        $directories = Directory::query()
            ->where('status', 'active')
            ->when($this->option('directory'), function ($query, string $directory) {
                $query->where(fn ($match) => $match
                    ->where('domain', $directory)
                    ->orWhere('slug', $directory));
            })
            ->get();

        if ($directories->isEmpty()) {
            $this->error('Kontrol edilecek aktif rehber bulunamadı. Domain veya slug bilgisini doğrulayın.');

            return self::FAILURE;
        }

        $directoryIds = $directories->pluck('id');
        $activeCompanies = Company::withoutGlobalScope('directory')
            ->active()
            ->whereIn('directory_id', $directoryIds);
        $indexableCompanies = (clone $activeCompanies)->searchIndexable();

        $missingMetadata = $directories->filter(
            fn (Directory $directory): bool => blank($directory->meta_title) || blank($directory->meta_description)
        );
        $indexableCompanyCount = $indexableCompanies->count();
        $activeCompanyCount = (clone $activeCompanies)->count();
        $indexableCategoryCount = Category::withoutGlobalScope('directory')
            ->active()
            ->whereHas('companies', fn ($query) => $query
                ->searchIndexable()
                ->whereIn('directory_id', $directoryIds))
            ->count();
        $indexableCityCount = City::withoutGlobalScope('directory')
            ->whereHas('companies', fn ($query) => $query
                ->searchIndexable()
                ->whereIn('directory_id', $directoryIds))
            ->count();
        $indexablePostCount = Post::published()
            ->where('is_indexable', true)
            ->whereNull('canonical_url')
            ->count();

        $this->table(['SEO Kontrolü', 'Adet', 'Sonuç'], [
            ['Aktif rehber', $directories->count(), 'OK'],
            ['Ana sayfa meta bilgisi eksik', $missingMetadata->count(), $missingMetadata->isEmpty() ? 'OK' : 'UYARI'],
            ['Aktif firma', $activeCompanyCount, 'BİLGİ'],
            ['Sitemap’e girecek firma', $indexableCompanyCount, $indexableCompanyCount > 0 ? 'OK' : 'UYARI'],
            ['Noindex kalacak ham/eksik firma', max(0, $activeCompanyCount - $indexableCompanyCount), 'BİLGİ'],
            ['Sitemap’e girecek kategori', $indexableCategoryCount, $indexableCategoryCount > 0 ? 'OK' : 'BİLGİ'],
            ['Sitemap’e girecek şehir', $indexableCityCount, $indexableCityCount > 0 ? 'OK' : 'BİLGİ'],
            ['Indexlenebilir blog yazısı', $indexablePostCount, 'BİLGİ'],
        ]);

        if ($missingMetadata->isNotEmpty()) {
            $this->warn('Meta başlığı/açıklaması eksik rehberler: '.$missingMetadata->pluck('domain')->filter()->implode(', '));
        }

        $notReadyByDirectory = (clone $activeCompanies)
            ->whereNotIn('id', (clone $indexableCompanies)->select('id'))
            ->selectRaw('directory_id, COUNT(*) as aggregate')
            ->groupBy('directory_id')
            ->pluck('aggregate', 'directory_id');

        $directoriesWithNoIndexableCompanies = $directories->filter(
            fn (Directory $directory): bool => ! Company::withoutGlobalScope('directory')
                ->searchIndexable()
                ->where('directory_id', $directory->id)
                ->exists()
        );

        if ($directoriesWithNoIndexableCompanies->isNotEmpty()) {
            $this->warn('Henüz sitemap’e firma veremeyen rehberler: '.$directoriesWithNoIndexableCompanies->pluck('domain')->filter()->implode(', '));
        }

        if ($notReadyByDirectory->isNotEmpty()) {
            $this->line('Not: Noindex profiller; kategori, şehir, iletişim ve en az 80 karakter açıklama tamamlanınca otomatik olarak sitemap’e girer.');
        }

        return self::SUCCESS;
    }
}
