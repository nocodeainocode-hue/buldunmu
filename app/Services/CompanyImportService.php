<?php

namespace App\Services;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyImportBatch;
use App\Models\CompanyImportChange;
use App\Models\Directory;
use App\Models\District;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class CompanyImportService
{
    public const COLUMNS = [
        'external_id', 'directory', 'name', 'category', 'city', 'district',
        'phone', 'whatsapp', 'email', 'website', 'address', 'google_maps',
        'opening_hours', 'short_description', 'description', 'logo_url', 'status',
    ];

    public function rows(string $path, ?int $limit = null): array
    {
        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);
        $sheet = $reader->load($path)->getActiveSheet()->toArray(null, true, true, false);
        $header = array_shift($sheet) ?? [];
        $header = array_map(fn($value) => $this->normalizeHeader((string) $value), $header);
        $rows = [];

        foreach ($sheet as $values) {
            if ($limit !== null && count($rows) >= $limit) {
                break;
            }
            $row = [];
            foreach ($header as $index => $key) {
                if ($key !== '') {
                    $row[$key] = is_string($values[$index] ?? null) ? trim($values[$index]) : ($values[$index] ?? null);
                }
            }
            if (collect($row)->filter(fn($value) => filled($value))->isNotEmpty()) {
                $rows[] = $row;
            }
        }

        return $rows;
    }

    public function preview(string $path, array $directoryIds, int $limit = 15): array
    {
        $directories = Directory::whereIn('id', $directoryIds)->get()->keyBy('id');

        return collect($this->rows($path, $limit))->map(function (array $row, int $index) use ($directories) {
            $targets = $this->targetDirectories($row, $directories);
            $errors = [];
            if (blank($row['name'] ?? null)) $errors[] = 'Firma adı eksik';
            if ($targets->isEmpty()) $errors[] = 'Hedef rehber bulunamadı';
            if (blank($row['category'] ?? null)) $errors[] = 'Kategori eksik';
            if (blank($row['city'] ?? null)) $errors[] = 'Şehir eksik';

            return [
                'row' => $index + 2,
                'name' => $row['name'] ?? '',
                'category' => $row['category'] ?? '',
                'city' => $row['city'] ?? '',
                'directories' => $targets->pluck('domain')->implode(', '),
                'status' => $errors ? implode(', ', $errors) : 'Hazır',
                'valid' => !$errors,
            ];
        })->all();
    }

    public function process(CompanyImportBatch $batch): void
    {
        $batch->update(['status' => 'processing', 'started_at' => now(), 'errors' => []]);
        $options = $batch->options ?? [];
        $directories = Directory::whereIn('id', $options['directory_ids'] ?? [])->get()->keyBy('id');
        $stats = ['rows' => 0, 'created' => 0, 'updated' => 0, 'skipped' => 0, 'failed' => 0];
        $errors = [];

        try {
            foreach ($this->rows(Storage::disk('local')->path($batch->stored_path)) as $rowIndex => $row) {
                $stats['rows']++;
                $targets = $this->targetDirectories($row, $directories);

                if (blank($row['name'] ?? null) || $targets->isEmpty()) {
                    $stats['failed']++;
                    $errors[] = 'Satır ' . ($rowIndex + 2) . ': Firma adı veya hedef rehber eksik.';
                    continue;
                }

                foreach ($targets as $directory) {
                    try {
                        $result = DB::transaction(fn() => $this->importForDirectory($batch, $directory, $row, $options));
                        $stats[$result]++;
                    } catch (\Throwable $exception) {
                        $stats['failed']++;
                        if (count($errors) < 500) {
                            $errors[] = 'Satır ' . ($rowIndex + 2) . ' / ' . $directory->domain . ': ' . $exception->getMessage();
                        }
                    }
                }

                if ($stats['rows'] % 100 === 0) {
                    $batch->update(['stats' => $stats, 'errors' => $errors]);
                }
            }

            $batch->update(['status' => 'completed', 'stats' => $stats, 'errors' => $errors, 'completed_at' => now()]);
        } catch (\Throwable $exception) {
            $errors[] = $exception->getMessage();
            $batch->update(['status' => 'failed', 'stats' => $stats, 'errors' => $errors, 'completed_at' => now()]);
            throw $exception;
        }
    }

    public function rollback(CompanyImportBatch $batch): void
    {
        $batch->update(['status' => 'rolling_back']);

        $batch->changes()->latest('id')->each(function (CompanyImportChange $change) {
            DB::transaction(function () use ($change) {
                $company = Company::withoutGlobalScope('directory')->find($change->company_id);
                if ($change->action === 'created') {
                    $company?->delete();
                } elseif ($change->action === 'updated' && $company && $change->before_data) {
                    $company->update($change->before_data);
                }
            });
        });

        $batch->update(['status' => 'rolled_back', 'completed_at' => now()]);
    }

    private function importForDirectory(CompanyImportBatch $batch, Directory $directory, array $row, array $options): string
    {
        $category = $this->taxonomy(Category::class, $directory->id, $row['category'] ?? null, $options['auto_create_taxonomies'] ?? true);
        $city = $this->taxonomy(City::class, $directory->id, $row['city'] ?? null, $options['auto_create_taxonomies'] ?? true);
        if (!$category || !$city) {
            throw new \RuntimeException('Kategori veya şehir eşleştirilemedi.');
        }

        $district = null;
        if (filled($row['district'] ?? null)) {
            $district = District::withoutGlobalScope('directory')
                ->where('directory_id', $directory->id)->where('city_id', $city->id)
                ->whereRaw('LOWER(name) = ?', [Str::lower($row['district'])])->first();
            if (!$district && ($options['auto_create_taxonomies'] ?? true)) {
                $district = District::create([
                    'name' => $row['district'], 'slug' => $this->uniqueTaxonomySlug(District::class, $row['district'], $directory->id),
                    'city_id' => $city->id, 'directory_id' => $directory->id,
                ]);
            }
        }

        $data = array_filter([
            'external_id' => $row['external_id'] ?? null,
            'name' => trim($row['name']),
            'category_id' => $category->id,
            'city_id' => $city->id,
            'district_id' => $district?->id,
            'phone' => $row['phone'] ?? null,
            'whatsapp' => $row['whatsapp'] ?? null,
            'email' => $row['email'] ?? null,
            'website' => $row['website'] ?? null,
            'address' => $row['address'] ?? null,
            'google_maps_url' => $row['google_maps'] ?? null,
            'opening_hours' => $row['opening_hours'] ?? null,
            'short_description' => $row['short_description'] ?? null,
            'description' => $row['description'] ?? null,
            'status' => in_array($row['status'] ?? null, ['pending', 'active', 'passive'], true)
                ? $row['status'] : ($batch->default_status ?: 'pending'),
            'directory_id' => $directory->id,
        ], fn($value) => $value !== null && $value !== '');

        $existing = $this->findDuplicate($directory->id, $row, $city->id);
        $strategy = $batch->duplicate_strategy;
        if ($existing && $strategy === 'skip') return 'skipped';

        if (filled($row['logo_url'] ?? null)) {
            $logo = $this->downloadLogo($row['logo_url']);
            if ($logo) $data['logo'] = $logo;
        }

        if ($existing && $strategy === 'update') {
            $before = $existing->only(array_keys($data));
            $oldLogo = $existing->logo;
            $existing->update($data);
            $existing->refresh();
            if (isset($data['logo']) && $oldLogo && $oldLogo !== $existing->logo) {
                Storage::disk('public')->delete($oldLogo);
            }
            CompanyImportChange::create([
                'batch_id' => $batch->id, 'company_id' => $existing->id, 'directory_id' => $directory->id,
                'action' => 'updated', 'before_data' => $before, 'after_data' => $existing->fresh()->only(array_keys($data)),
                'created_at' => now(),
            ]);
            return 'updated';
        }

        $data['import_batch_id'] = $batch->id;
        $company = Company::withoutGlobalScope('directory')->create($data);
        CompanyImportChange::create([
            'batch_id' => $batch->id, 'company_id' => $company->id, 'directory_id' => $directory->id,
            'action' => 'created', 'after_data' => $company->toArray(), 'created_at' => now(),
        ]);

        return 'created';
    }

    private function targetDirectories(array $row, $allowedDirectories)
    {
        $requested = collect(preg_split('/[,;|]/', (string) ($row['directory'] ?? '')))->map(fn($value) => trim($value))->filter();
        if ($requested->isEmpty() || $requested->contains(fn($value) => Str::upper($value) === 'ALL')) {
            return $allowedDirectories->values();
        }

        return $allowedDirectories->filter(fn(Directory $directory) => $requested->contains(fn($value) =>
            Str::lower($value) === Str::lower($directory->domain) || Str::lower($value) === Str::lower($directory->slug)
        ))->values();
    }

    private function taxonomy(string $model, int $directoryId, ?string $name, bool $create)
    {
        if (blank($name)) return null;
        $record = $model::withoutGlobalScope('directory')->where('directory_id', $directoryId)
            ->whereRaw('LOWER(name) = ?', [Str::lower(trim($name))])->first();
        if (!$record && $create) {
            $record = $model::withoutGlobalScope('directory')->create([
                'name' => trim($name), 'slug' => $this->uniqueTaxonomySlug($model, $name, $directoryId),
                'status' => 'active', 'directory_id' => $directoryId,
            ]);
        }
        return $record;
    }

    private function findDuplicate(int $directoryId, array $row, int $cityId): ?Company
    {
        return Company::withoutGlobalScope('directory')->where('directory_id', $directoryId)
            ->where(function ($query) use ($row, $cityId) {
                if (filled($row['external_id'] ?? null)) $query->orWhere('external_id', $row['external_id']);
                if (filled($row['website'] ?? null)) $query->orWhere('website', $row['website']);
                if (filled($row['phone'] ?? null)) $query->orWhere('phone', $row['phone']);
                $query->orWhere(fn($nested) => $nested->where('name', $row['name'])->where('city_id', $cityId));
            })->first();
    }

    private function uniqueTaxonomySlug(string $model, string $name, int $directoryId): string
    {
        $base = Str::slug($name) ?: 'kayit'; $slug = $base; $counter = 2;
        while ($model::withoutGlobalScope('directory')->where('directory_id', $directoryId)->where('slug', $slug)->exists()) $slug = $base . '-' . $counter++;
        return $slug;
    }

    private function normalizeHeader(string $header): string
    {
        $key = Str::snake(Str::ascii(trim($header)));
        return match ($key) {
            'firma', 'firma_adi', 'company', 'company_name' => 'name',
            'rehber', 'rehberler', 'domain', 'domains', 'target' => 'directory',
            'kategori' => 'category', 'sehir', 'il' => 'city', 'ilce' => 'district',
            'telefon' => 'phone', 'eposta', 'e_posta' => 'email', 'web', 'web_sitesi' => 'website',
            'adres' => 'address', 'harita', 'maps', 'google_maps_iframe' => 'google_maps',
            'calisma_saatleri' => 'opening_hours', 'kisa_aciklama' => 'short_description',
            'aciklama' => 'description', 'logo' => 'logo_url', 'durum' => 'status',
            default => $key,
        };
    }

    private function downloadLogo(string $url): ?string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL) || !in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)) {
            return null;
        }

        try {
            $response = Http::timeout(15)->retry(1, 250)->get($url);
            if (!$response->successful() || strlen($response->body()) > 5 * 1024 * 1024) return null;
            $mime = Str::before((string) $response->header('Content-Type'), ';');
            $extension = match ($mime) {
                'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp',
                'image/gif' => 'gif', 'image/svg+xml' => 'svg', default => null,
            };
            if (!$extension) return null;
            $path = 'companies/logos/import-' . Str::uuid() . '.' . $extension;
            Storage::disk('public')->put($path, $response->body());
            return $path;
        } catch (\Throwable) {
            return null;
        }
    }
}
