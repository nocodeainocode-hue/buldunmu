<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use BelongsToDirectory;
    protected $fillable = [
        'name', 'slug', 'plate_code',
        'meta_title', 'meta_description', 'directory_id',
    ];

    public function districts()
    {
        return $this->hasMany(District::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
