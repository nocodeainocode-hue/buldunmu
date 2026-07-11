<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyImportChange extends Model
{
    public $timestamps = false;

    protected $fillable = ['batch_id', 'company_id', 'directory_id', 'action', 'before_data', 'after_data', 'created_at'];

    protected $casts = [
        'before_data' => 'array',
        'after_data' => 'array',
        'created_at' => 'datetime',
    ];
}
