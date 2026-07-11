<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;

class DiscoveredCompany extends Model
{
    use BelongsToDirectory;

    protected $fillable = [
        'name', 'phone', 'address', 'website', 'logo_url', 'email',
        'description', 'source', 'search_keyword', 'search_city',
        'raw_data', 'status', 'approved_company_id', 'admin_notes',
        'directory_id',
    ];

    protected $casts = [
        'raw_data' => 'array',
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
            'status' => 'active',
            'directory_id' => $this->directory_id,
        ];

        // Handle category_id and city_id from overrides
        $categoryId = $overrides['category_id'] ?? null;
        $cityId = $overrides['city_id'] ?? null;
        unset($overrides['category_id'], $overrides['city_id']);

        // Auto-assign defaults if not explicitly provided (required FK on companies table)
        $directoryId = $this->directory_id;
        $data['category_id'] = $categoryId ?: $this->findOrCreateDefaultCategory($directoryId);
        $data['city_id'] = $cityId ?: $this->findOrCreateDefaultCity($directoryId);

        // Download logo if available
        if (!empty($this->logo_url) && empty($overrides['logo'])) {
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

    /**
     * Find or create a default "Genel" category for the directory.
     */
    protected function findOrCreateDefaultCategory(?int $directoryId): int
    {
        $category = Category::where('directory_id', $directoryId)->first();

        if (!$category) {
            $category = Category::create([
                'name' => 'Genel',
                'slug' => 'genel',
                'status' => 'active',
                'directory_id' => $directoryId,
            ]);
        }

        return $category->id;
    }

    /**
     * Find or create a default city for the directory.
     * Tries to match the search_city first, then falls back to a default.
     */
    protected function findOrCreateDefaultCity(?int $directoryId): int
    {
        // Try to match search_city
        if (!empty($this->search_city)) {
            $city = City::where('directory_id', $directoryId)
                ->where('name', 'like', '%' . $this->search_city . '%')
                ->first();
            if ($city) {
                return $city->id;
            }

            // Create city from search_city
            $city = City::create([
                'name' => $this->search_city,
                'slug' => \Illuminate\Support\Str::slug($this->search_city),
                'directory_id' => $directoryId,
            ]);
            return $city->id;
        }

        // Ultimate fallback
        $city = City::where('directory_id', $directoryId)->first();

        if (!$city) {
            $city = City::create([
                'name' => 'Türkiye',
                'slug' => 'turkiye',
                'directory_id' => $directoryId,
            ]);
        }

        return $city->id;
    }

    protected function downloadLogo(): ?string
    {
        $client = new \GuzzleHttp\Client(['timeout' => 15]);
        $response = $client->get($this->logo_url);

        $ext = pathinfo(parse_url($this->logo_url, PHP_URL_PATH), PATHINFO_EXTENSION);
        if (empty($ext) || !in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
            $ext = 'png';
        }

        $filename = 'companies/logos/' . \Illuminate\Support\Str::uuid() . '.' . $ext;
        \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $response->getBody()->getContents());

        return $filename;
    }
}
