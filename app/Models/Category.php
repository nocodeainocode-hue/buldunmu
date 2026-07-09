<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use BelongsToDirectory;

    protected $fillable = [
        'name', 'slug', 'description', 'icon',
        'meta_title', 'meta_description', 'status', 'directory_id',
    ];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
