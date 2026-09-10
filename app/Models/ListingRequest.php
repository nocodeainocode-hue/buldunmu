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
        'message', 'status', 'directory_id',
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

    public function approveToCompany(?int $directoryId = null): Company
    {
        $directoryId ??= $this->directory_id;

        if (! $directoryId || ! Directory::whereKey($directoryId)->exists()) {
            throw new InvalidArgumentException('Firma talebi için geçerli bir hedef rehber seçilmelidir.');
        }

        return DB::transaction(function () use ($directoryId): Company {
            $this->update(['status' => 'reviewed']);

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
