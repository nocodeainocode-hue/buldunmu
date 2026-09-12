<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\District;
use App\Models\SiteSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SystemPreflight extends Command
{
    protected $signature = 'system:preflight';

    protected $description = 'Çoklu rehber altyapısındaki katalog ve tenant sorunlarını salt okunur olarak denetler';

    public function handle(): int
    {
        $directories = Directory::query()->get();
        $sharedCategories = Category::withoutGlobalScope('directory')->whereNull('directory_id')->count();
        $sharedCities = City::withoutGlobalScope('directory')->whereNull('directory_id')->count();
        $sharedDistricts = District::withoutGlobalScope('directory')->whereNull('directory_id')->count();

        $duplicateDomains = $directories
            ->filter(fn (Directory $directory): bool => filled($directory->domain))
            ->groupBy(fn (Directory $directory): string => Directory::normalizeDomain($directory->domain) ?? '')
            ->filter(fn ($matches): bool => $matches->count() > 1);

        $missingSettings = $directories->filter(fn (Directory $directory): bool => ! SiteSetting::withoutGlobalScope('directory')
            ->where('directory_id', $directory->id)
            ->exists());

        $tenantCategories = Category::withoutGlobalScope('directory')->whereNotNull('directory_id')->count();
        $tenantCities = City::withoutGlobalScope('directory')->whereNotNull('directory_id')->count();
        $tenantDistricts = District::withoutGlobalScope('directory')->whereNotNull('directory_id')->count();
        $globalCompanies = Company::withoutGlobalScope('directory')->whereNull('directory_id')->count();
        $missingDomains = $directories->filter(fn (Directory $directory): bool => blank($directory->domain));
        $duplicateSharedCategorySlugs = Category::withoutGlobalScope('directory')
            ->whereNull('directory_id')
            ->selectRaw('slug, COUNT(*) as aggregate')
            ->groupBy('slug')
            ->havingRaw('COUNT(*) > 1')
            ->count();
        $duplicateSharedCitySlugs = City::withoutGlobalScope('directory')
            ->whereNull('directory_id')
            ->selectRaw('slug, COUNT(*) as aggregate')
            ->groupBy('slug')
            ->havingRaw('COUNT(*) > 1')
            ->count();

        $rows = [
            ['Rehber', $directories->count(), $directories->count() > 0 ? 'OK' : 'HATA'],
            ['Aktif rehber', $directories->where('status', 'active')->count(), 'BİLGİ'],
            ['Ortak kategori', $sharedCategories, $sharedCategories > 0 ? 'OK' : 'HATA'],
            ['Ortak şehir', $sharedCities, $sharedCities === 81 ? 'OK' : 'UYARI (81 olmalı)'],
            ['Ortak ilçe', $sharedDistricts, $sharedDistricts >= 900 ? 'OK' : 'UYARI'],
            ['Ayrı kategori kopyası', $tenantCategories, $tenantCategories === 0 ? 'OK' : 'UYARI'],
            ['Ayrı şehir kopyası', $tenantCities, $tenantCities === 0 ? 'OK' : 'UYARI'],
            ['Ayrı ilçe kopyası', $tenantDistricts, $tenantDistricts === 0 ? 'OK' : 'UYARI'],
            ['Ayarı eksik rehber', $missingSettings->count(), $missingSettings->isEmpty() ? 'OK' : 'HATA'],
            ['Domainsiz rehber', $missingDomains->count(), $missingDomains->isEmpty() ? 'OK' : 'HATA'],
            ['Tekrarlanan domain', $duplicateDomains->count(), $duplicateDomains->isEmpty() ? 'OK' : 'HATA'],
            ['Tüm rehberlerde yayınlanan firma', $globalCompanies, 'BİLGİ'],
            ['Tekrarlanan ortak kategori slug', $duplicateSharedCategorySlugs, $duplicateSharedCategorySlugs === 0 ? 'OK' : 'HATA'],
            ['Tekrarlanan ortak şehir slug', $duplicateSharedCitySlugs, $duplicateSharedCitySlugs === 0 ? 'OK' : 'HATA'],
        ];

        if (Schema::hasTable('failed_jobs')) {
            $failedJobs = DB::table('failed_jobs')->count();
            $rows[] = ['Başarısız kuyruk işi', $failedJobs, $failedJobs === 0 ? 'OK' : 'UYARI'];
        }

        $this->table(['Kontrol', 'Adet', 'Sonuç'], $rows);

        if ($missingSettings->isNotEmpty()) {
            $this->warn('Ayarı eksik: '.$missingSettings->pluck('domain')->filter()->implode(', '));
        }

        if ($duplicateDomains->isNotEmpty()) {
            $this->error('Domain çakışması: '.$duplicateDomains->keys()->implode(', '));
        }

        $hasBlockingIssue = $directories->isEmpty()
            || $sharedCategories === 0
            || $missingSettings->isNotEmpty()
            || $missingDomains->isNotEmpty()
            || $duplicateDomains->isNotEmpty()
            || $duplicateSharedCategorySlugs > 0
            || $duplicateSharedCitySlugs > 0;

        $this->{$hasBlockingIssue ? 'error' : 'info'}(
            $hasBlockingIssue
                ? 'Ön kontrol başarısız. HATA satırlarını düzeltmeden yeni rehber grubuna geçmeyin.'
                : 'Ön kontrol tamamlandı. HATA seviyesinde sorun bulunmadı.'
        );

        return $hasBlockingIssue ? self::FAILURE : self::SUCCESS;
    }
}
