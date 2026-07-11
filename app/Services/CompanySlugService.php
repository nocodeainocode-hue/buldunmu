<?php

namespace App\Services;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\District;
use Illuminate\Support\Str;

class CompanySlugService
{
    public const PATTERNS = [
        '{name}' => 'Firma adı — eyup-market',
        '{name}-{city}' => 'Firma + şehir — eyup-market-istanbul',
        '{city}-{name}' => 'Şehir + firma — istanbul-eyup-market',
        '{name}-{district}' => 'Firma + ilçe — eyup-market-kadikoy',
        '{district}-{name}' => 'İlçe + firma — kadikoy-eyup-market',
        '{category}-{name}' => 'Kategori + firma — market-eyup-market',
        '{name}-{category}' => 'Firma + kategori — eyup-market-market',
        '{city}-{category}-{name}' => 'Şehir + kategori + firma',
        '{category}-{city}-{name}' => 'Kategori + şehir + firma',
        '{name}-{district}-{city}' => 'Firma + ilçe + şehir',
    ];

    public function generate(Company $company, ?string $submittedSlug = null): string
    {
        $plainNameSlug = Str::slug($company->name);
        $hasCustomSlug = filled($submittedSlug) && Str::slug($submittedSlug) !== $plainNameSlug;

        if ($hasCustomSlug) {
            return $this->unique(Str::slug($submittedSlug), $company->directory_id, $company->id);
        }

        $directory = $company->directory_id ? Directory::find($company->directory_id) : null;
        $pattern = $directory?->slug_pattern;
        if (!array_key_exists($pattern, self::PATTERNS)) {
            $pattern = '{name}-{city}';
        }

        $candidate = $this->render($pattern, [
            'name' => $company->name,
            'city' => $this->modelName(City::class, $company->city_id),
            'district' => $this->modelName(District::class, $company->district_id),
            'category' => $this->modelName(Category::class, $company->category_id),
        ]);

        return $this->unique($candidate ?: $plainNameSlug ?: 'firma', $company->directory_id, $company->id);
    }

    public function render(string $pattern, array $values): string
    {
        $rendered = strtr($pattern, [
            '{name}' => (string) ($values['name'] ?? ''),
            '{city}' => (string) ($values['city'] ?? ''),
            '{district}' => (string) ($values['district'] ?? ''),
            '{category}' => (string) ($values['category'] ?? ''),
        ]);

        return Str::slug($rendered);
    }

    public static function patternForPosition(int $position): string
    {
        $patterns = array_keys(self::PATTERNS);
        return $patterns[$position % count($patterns)];
    }

    public static function selectOptions(): array
    {
        return self::PATTERNS;
    }

    private function unique(string $base, ?int $directoryId, ?int $ignoreId = null): string
    {
        $slug = $base;
        $counter = 2;

        while (Company::withoutGlobalScope('directory')
            ->where('directory_id', $directoryId)
            ->when($ignoreId, fn($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    private function modelName(string $model, ?int $id): string
    {
        if (!$id) return '';
        return (string) $model::withoutGlobalScope('directory')->whereKey($id)->value('name');
    }
}
