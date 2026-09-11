<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class DiscoveredCompany extends Model
{
    use BelongsToDirectory;

    public function allowsSharedDirectoryRecords(): bool
    {
        return false;
    }

    protected $fillable = [
        'name', 'external_id', 'phone', 'address', 'latitude', 'longitude',
        'opening_hours', 'website', 'logo_url', 'email', 'description',
        'source', 'source_url', 'search_keyword', 'search_city',
        'raw_data', 'status', 'approved_company_id', 'admin_notes',
        'directory_id',
    ];

    protected $casts = [
        'raw_data' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function approvedCompany()
    {
        return $this->belongsTo(Company::class, 'approved_company_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Approve this discovered company and create a real Company record.
     */
    public function approve(?array $overrides = null): Company
    {
        $overrides = $overrides ?? [];

        $data = [
            'name' => $overrides['name'] ?? $this->name,
            'phone' => $overrides['phone'] ?? $this->phone,
            'address' => $overrides['address'] ?? $this->address,
            'website' => $overrides['website'] ?? $this->website,
            'email' => $overrides['email'] ?? $this->email,
            'description' => $overrides['description'] ?? $this->description,
            'external_id' => $overrides['external_id'] ?? $this->external_id,
            'latitude' => $overrides['latitude'] ?? $this->latitude,
            'longitude' => $overrides['longitude'] ?? $this->longitude,
            'opening_hours' => $overrides['opening_hours'] ?? $this->opening_hours,
            'google_maps_url' => $overrides['google_maps_url']
                ?? data_get($this->raw_data, 'google_maps_url')
                ?? ($this->latitude && $this->longitude
                    ? "https://www.google.com/maps/search/?api=1&query={$this->latitude},{$this->longitude}"
                    : null),
            'status' => 'active',
            'directory_id' => $this->directory_id,
        ];

        // Resolve every approved record against the shared catalog.
        $categoryId = $overrides['category_id'] ?? null;
        $cityId = $overrides['city_id'] ?? null;
        $districtId = $overrides['district_id'] ?? null;

        $data['category_id'] = $this->resolveSharedCategory($categoryId);
        $data['city_id'] = $this->resolveSharedCity($cityId);
        $data['district_id'] = $this->resolveSharedDistrict($districtId, $data['city_id']);

        // Download logo if available
        if (! empty($this->logo_url) && empty($overrides['logo'])) {
            try {
                $logoPath = $this->downloadLogo();
                if ($logoPath) {
                    $data['logo'] = $logoPath;
                }
            } catch (\Exception $e) {
                // Logo download failure is non-critical
            }
        }

        $company = Company::create($data);

        $this->update([
            'status' => 'approved',
            'approved_company_id' => $company->id,
        ]);

        return $company;
    }

    protected function resolveSharedCategory(mixed $categoryId): int
    {
        $selected = filled($categoryId)
            ? Category::withoutGlobalScope('directory')->find($categoryId)
            : null;
        $slug = $selected?->slug ?: 'genel';

        $category = Category::withoutGlobalScope('directory')
            ->whereNull('directory_id')
            ->where('slug', $slug)
            ->first();

        if (! $category) {
            $category = Category::create([
                'name' => $selected?->name ?: 'Genel',
                'slug' => $slug,
                'status' => 'active',
                'directory_id' => null,
            ]);
        }

        return $category->id;
    }

    protected function resolveSharedCity(mixed $cityId): int
    {
        $selected = filled($cityId)
            ? City::withoutGlobalScope('directory')->find($cityId)
            : null;
        $name = $selected?->name ?: trim((string) $this->search_city);
        $slug = $selected?->slug ?: Str::slug($name);

        $city = filled($slug)
            ? City::withoutGlobalScope('directory')->whereNull('directory_id')->where('slug', $slug)->first()
            : null;

        if (! $city) {
            throw new RuntimeException('Şehir ortak katalogda bulunamadı. Detaylı onaydan geçerli bir şehir seçin.');
        }

        return $city->id;
    }

    protected function resolveSharedDistrict(mixed $districtId, int $cityId): ?int
    {
        if (blank($districtId)) {
            return null;
        }

        $selected = District::withoutGlobalScope('directory')->find($districtId);

        if (! $selected) {
            return null;
        }

        return District::withoutGlobalScope('directory')
            ->whereNull('directory_id')
            ->where('city_id', $cityId)
            ->where('slug', $selected->slug)
            ->value('id');
    }

    protected function downloadLogo(): ?string
    {
        $client = new Client(['timeout' => 15]);
        $response = $client->get($this->logo_url);

        $ext = pathinfo(parse_url($this->logo_url, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (empty($ext) || ! in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
            $ext = 'png';
        }

        $filename = 'companies/logos/'.Str::uuid().'.'.$ext;
        Storage::disk('public')->put($filename, $response->getBody()->getContents());

        return $filename;
    }
}
