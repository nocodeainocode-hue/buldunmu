<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CompanyImage extends Model
{
    protected $fillable = ['company_id', 'image_path', 'alt_text', 'sort_order'];

    protected static function booted(): void
    {
        static::deleting(function (self $image) {
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        });

        static::saving(function (self $image) {
            // Validate image count per company (max 20)
            if ($image->company_id && !$image->exists) {
                $count = static::where('company_id', $image->company_id)->count();
                if ($count >= 20) {
                    throw new \RuntimeException('Bir firmaya en fazla 20 fotoğraf eklenebilir.');
                }
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
