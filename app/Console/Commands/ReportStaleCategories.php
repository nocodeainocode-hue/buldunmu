<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\CategoryDirectorySetting;
use App\Models\Company;
use App\Models\ListingRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReportStaleCategories extends Command
{
    protected $signature = 'categories:report-stale
        {--merge : Move related records to the shared catalog and delete tenant copies}';

    protected $description = 'Eski rehbere özel kategori kopyalarını raporlar ve isteğe bağlı birleştirir';

    public function handle(): int
    {
        $copies = Category::withoutGlobalScope('directory')
            ->whereNotNull('directory_id')
            ->with('directory')
            ->orderBy('slug')
            ->get();

        if ($copies->isEmpty()) {
            $this->info('Eski kategori kopyası bulunamadı.');

            return self::SUCCESS;
        }

        $rows = $copies->map(function (Category $category): array {
            $companyCount = Company::withoutGlobalScope('directory')->where('category_id', $category->id)->count();

            return [
                $category->id,
                $category->name,
                $category->slug,
                $category->directory?->domain ?? '-',
                $companyCount,
            ];
        });

        $this->table(['ID', 'Kategori', 'Slug', 'Rehber', 'Firma'], $rows->all());
        $this->info($copies->count().' eski kategori kopyası bulundu.');

        if (! $this->option('merge')) {
            $this->comment('Birleştirmek için komutu --merge seçeneğiyle tekrar çalıştırın.');

            return self::SUCCESS;
        }

        $movedCompanies = 0;

        foreach ($copies as $copy) {
            $movedCompanies += $this->merge($copy);
        }

        $this->info("{$movedCompanies} firma ortak kategorilere taşındı; eski kopyalar silindi.");

        return self::SUCCESS;
    }

    private function merge(Category $copy): int
    {
        return DB::transaction(function () use ($copy): int {
            $canonical = Category::withoutGlobalScope('directory')->firstOrCreate(
                ['slug' => $copy->slug, 'directory_id' => null],
                [
                    'name' => $copy->name,
                    'description' => $copy->description,
                    'icon' => $copy->icon,
                    'meta_title' => $copy->meta_title,
                    'meta_description' => $copy->meta_description,
                    'status' => $copy->status,
                    'directory_id' => null,
                ],
            );

            CategoryDirectorySetting::query()
                ->where('category_id', $copy->id)
                ->get()
                ->each(function (CategoryDirectorySetting $setting) use ($canonical): void {
                    CategoryDirectorySetting::query()->updateOrCreate(
                        [
                            'category_id' => $canonical->id,
                            'directory_id' => $setting->directory_id,
                        ],
                        [
                            'is_visible' => $setting->is_visible,
                            'sort_order' => $setting->sort_order,
                        ],
                    );
                    $setting->delete();
                });

            $companies = Company::withoutGlobalScope('directory')->where('category_id', $copy->id);
            $movedCompanies = $companies->count();
            $companies->update(['category_id' => $canonical->id]);

            ListingRequest::withoutGlobalScope('directory')
                ->where('category_id', $copy->id)
                ->update(['category_id' => $canonical->id]);

            $copy->delete();

            return $movedCompanies;
        });
    }
}
