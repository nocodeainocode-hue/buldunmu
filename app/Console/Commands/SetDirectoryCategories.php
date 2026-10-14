<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\CategoryDirectorySetting;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Bir rehberde yalnızca verilen kategorilerin görünmesini sağlar (sırasıyla).
 * - Eksik kategoriler ortak katalogda oluşturulur; komutun yeni oluşturduğu kategoriler diğer rehberlerde gizlenir.
 * - Bu rehberde diğer tüm kategoriler gizlenir (silinmez).
 * - Rehbere ait firmalar ad ve eski kategoriye göre yeni kategorilere taşınır; eşleşmeyenler olduğu gibi kalır ve listelenir.
 * Varsayılan kuru çalıştırmadır; değişiklik için --apply.
 */
class SetDirectoryCategories extends Command
{
    protected $signature = 'directory:set-categories
        {domain : Rehberin alan adı (ör. kuaforrehberi.com.tr)}
        {names* : Görünecek kategoriler, görünme sırasıyla}
        {--apply : Değişiklikleri uygula (yoksa yalnızca plan gösterilir)}';

    protected $description = 'Bir rehberde yalnızca verilen kategorileri gösterir ve rehberin firmalarını bu kategorilere taşır';

    /** Firma adı + eski kategori adında aranır; ilk eşleşen kural kazanır. Hedef kategori listede yoksa kural atlanır. */
    private const RULES = [
        ['tirnak-salonu', '/t[ıi]rnak|nail|man[iı]k[uü]r|pedik[uü]r/u'],
        ['epilasyon', '/epilasyon|lazer|a[gğ]da/u'],
        ['spa-merkezi', '/\bspa\b|masaj|hamam|wellness/u'],
        ['erkek-kuafor', '/erkek|berber|barber|\bmen\b|gentleman/u'],
        ['kadin-kuafor', '/bayan|kad[ıi]n|\blady\b|women/u'],
        ['unisex-kuafor', '/unisex/u'],
        ['guzellik-merkezi', '/g[uü]zellik|cilt|estetik|beauty|makyaj|kirpik/u'],
        ['unisex-kuafor', '/kuaf[oö]r|\bhair\b|sa[cç] /u'],
    ];

    private const ICONS = ['unisex-kuafor' => '✂️', 'kadin-kuafor' => '💇‍♀️', 'erkek-kuafor' => '💈', 'spa-merkezi' => '🧖', 'epilasyon' => '✨', 'tirnak-salonu' => '💅', 'guzellik-merkezi' => '🌸'];

    public function handle(): int
    {
        $directory = Directory::where('domain', Directory::normalizeDomain($this->argument('domain')))->first();
        if (! $directory) {
            $this->error('Rehber bulunamadı: '.$this->argument('domain'));

            return self::FAILURE;
        }

        $apply = (bool) $this->option('apply');
        $names = array_values(array_unique(array_map('trim', $this->argument('names'))));

        return DB::transaction(function () use ($directory, $names, $apply): int {
            $targets = [];
            $created = [];
            foreach ($names as $i => $name) {
                $slug = Str::slug($name);
                $category = Category::withoutGlobalScope('directory')->where('slug', $slug)->first();
                if (! $category && $apply) {
                    $category = Category::withoutGlobalScope('directory')->create([
                        'name' => $name, 'slug' => $slug, 'status' => 'active', 'directory_id' => null, 'icon' => self::ICONS[$slug] ?? null,
                        'description' => $name.' işletmeleri',
                    ]);
                    $created[] = $category->id;
                }
                $this->line(sprintf('%d. %s (%s)%s', $i + 1, $name, $slug, $category ? '' : '  → oluşturulacak'));
                if ($category) {
                    if ($apply && $category->status !== 'active') {
                        $category->update(['status' => 'active']);
                    }
                    $targets[$slug] = $category;
                }
            }

            $hidden = Category::withoutGlobalScope('directory')->whereNotIn('slug', array_map(fn ($n) => Str::slug($n), $names))->count();
            $this->info("Bu rehberde gizlenecek diğer kategori: {$hidden}");

            if ($apply) {
                foreach (array_values($targets) as $i => $category) {
                    CategoryDirectorySetting::updateOrCreate(['category_id' => $category->id, 'directory_id' => $directory->id], ['is_visible' => true, 'sort_order' => $i + 1]);
                }
                Category::withoutGlobalScope('directory')->whereNotIn('id', collect($targets)->pluck('id'))->pluck('id')
                    ->each(fn ($id) => CategoryDirectorySetting::updateOrCreate(['category_id' => $id, 'directory_id' => $directory->id], ['is_visible' => false, 'sort_order' => null]));
                // Yeni açılan kategoriler (ör. "Unisex Kuaför") başka rehberlerde birden görünmesin.
                Directory::where('id', '!=', $directory->id)->pluck('id')->each(function ($otherId) use ($created): void {
                    foreach ($created as $categoryId) {
                        CategoryDirectorySetting::firstOrCreate(['category_id' => $categoryId, 'directory_id' => $otherId], ['is_visible' => false]);
                    }
                });
            }

            // Firmaları taşı (yalnızca bu rehbere ait olanlar).
            $targetIds = collect($targets)->pluck('id')->all();
            $moved = [];
            $left = [];
            Company::withoutGlobalScope('directory')->where('directory_id', $directory->id)->with(['category' => fn ($q) => $q->withoutGlobalScope('directory')])
                ->get()->each(function (Company $company) use ($targets, $targetIds, $apply, &$moved, &$left): void {
                    if (in_array($company->category_id, $targetIds, true)) {
                        return;
                    }
                    // Önce firma adı, sonra eski kategori adı: "Selin Kuaför" (Güzellik ve Kişisel Bakım) → Unisex Kuaför.
                    $slug = null;
                    foreach ([$company->name, $company->category?->name ?? ''] as $text) {
                        $haystack = mb_strtolower(strtr($text.' ', ['I' => 'ı', 'İ' => 'i']), 'UTF-8');
                        foreach (self::RULES as [$ruleSlug, $pattern]) {
                            if (isset($targets[$ruleSlug]) && preg_match($pattern, $haystack)) {
                                $slug = $ruleSlug;
                                break 2;
                            }
                        }
                    }
                    if (! $slug) {
                        $left[] = [$company->id, $company->name, $company->category?->name ?? '-'];

                        return;
                    }
                    $moved[] = [$company->id, $company->name, $company->category?->name ?? '-', $targets[$slug]->name];
                    if ($apply) {
                        $company->update(['category_id' => $targets[$slug]->id]);
                    }
                });

            $this->info(count($moved).' firma yeni kategoriye '.($apply ? 'taşındı' : 'taşınacak').'.');
            if ($moved) {
                $this->table(['ID', 'Firma', 'Eski kategori', 'Yeni kategori'], $moved);
            }
            if ($left) {
                $this->warn(count($left).' firma eşleşmedi, kategorisi değişmedi (bu rehberde kategori sayfalarında görünmez):');
                $this->table(['ID', 'Firma', 'Kategori'], $left);
            }
            if ($apply) {
                \App\Support\ModelCache::bump('dir.'.$directory->id);
            } else {
                $this->comment('Kuru çalıştırma. Uygulamak için aynı komutu --apply ile çalıştırın.');
            }

            return self::SUCCESS;
        });
    }
}
