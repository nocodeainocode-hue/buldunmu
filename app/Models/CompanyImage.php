<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyImage extends Model
{
    protected $fillable = ['company_id', 'image_path', 'sort_order'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
