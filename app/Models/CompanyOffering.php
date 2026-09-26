<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CompanyOffering extends Model
{
    use BelongsToDirectory;

    protected static function booted(): void
    {
        static::updated(function (self $offering): void {
            if ($offering->wasChanged('image_path') && $offering->getOriginal('image_path')) {
                Storage::disk('public')->delete($offering->getOriginal('image_path'));
            }
        });

        static::deleting(function (self $offering): void {
            if ($offering->image_path) {
                Storage::disk('public')->delete($offering->image_path);
            }
        });
    }

    protected $fillable = [
        'company_id', 'directory_id', 'type', 'name', 'description',
        'image_path', 'price', 'status', 'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function allowsSharedDirectoryRecords(): bool
    {
        return false;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
