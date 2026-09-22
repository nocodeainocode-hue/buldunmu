<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ListingRequest extends Model
{
    use BelongsToDirectory;

    public function allowsSharedDirectoryRecords(): bool
    {
        return false;
    }

    protected $fillable = [
        'company_name', 'contact_name', 'phone', 'whatsapp',
        'email', 'website', 'category_id', 'city_id', 'district_id',
        'requested_category', 'message', 'status', 'directory_id', 'claim_company_id', 'source',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function claimCompany()
    {
        return $this->belongsTo(Company::class, 'claim_company_id');
    }

    public function approveToCompany(?int $directoryId = null): Company
    {
        $directoryId ??= $this->directory_id;

        if (! $directoryId || ! Directory::whereKey($directoryId)->exists()) {
            throw new InvalidArgumentException('Firma talebi için geçerli bir hedef rehber seçilmelidir.');
        }

        if (! $this->category_id || ! Category::withoutGlobalScope('directory')->whereKey($this->category_id)->exists()) {
            throw new InvalidArgumentException('Firma yayına alınmadan önce geçerli bir kategori seçilmelidir.');
        }

        return DB::transaction(function () use ($directoryId): Company {
            $this->update(['status' => 'reviewed']);

            if ($this->claim_company_id) {
                $company = Company::withoutGlobalScope('directory')->find($this->claim_company_id);

                if (! $company || ($company->directory_id !== null && (int) $company->directory_id !== (int) $directoryId)) {
                    throw new InvalidArgumentException('Sahiplenme talebi, kaynak rehberdeki mevcut firmayla eşleşmiyor.');
                }

                if ($company->directory_id === null) {
                    $company = Company::create([
                        'name' => $this->company_name,
                        'directory_id' => $directoryId,
                        'category_id' => $this->category_id,
                        'city_id' => $this->city_id,
                        'district_id' => $this->district_id,
                        'phone' => $this->phone,
                        'whatsapp' => $this->whatsapp,
                        'email' => $this->email,
                        'website' => $this->website,
                        'status' => 'active',
                    ]);

                    $this->update([
                        'directory_id' => $directoryId,
                        'status' => 'approved',
                    ]);

                    return $company;
                }

                $company->fill(array_filter([
                    'name' => $this->company_name,
                    'category_id' => $this->category_id,
                    'city_id' => $this->city_id,
                    'district_id' => $this->district_id,
                    'phone' => $this->phone,
                    'whatsapp' => $this->whatsapp,
                    'email' => $this->email,
                    'website' => $this->website,
                ], fn ($value) => $value !== null));
                if ($this->source === 'owner_registration' && $company->status === 'pending') {
                    $company->status = 'active';
                }
                $company->save();

                $this->update([
                    'directory_id' => $directoryId,
                    'status' => 'approved',
                ]);

                return $company;
            }

            $company = Company::create([
                'name' => $this->company_name,
                'directory_id' => $directoryId,
                'category_id' => $this->category_id,
                'city_id' => $this->city_id,
                'district_id' => $this->district_id,
                'phone' => $this->phone,
                'whatsapp' => $this->whatsapp,
                'email' => $this->email,
                'website' => $this->website,
                'status' => 'active',
            ]);

            $this->update([
                'directory_id' => $directoryId,
                'status' => 'approved',
            ]);

            return $company;
        });
    }
}
