<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use BelongsToDirectory;
    protected $fillable = ['city_id', 'name', 'slug', 'directory_id'];

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
