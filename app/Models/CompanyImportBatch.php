<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyImportBatch extends Model
{
    protected $fillable = [
        'user_id', 'filename', 'stored_path', 'status', 'duplicate_strategy',
        'default_status', 'options', 'stats', 'errors', 'started_at', 'completed_at',
    ];

    protected $casts = [
        'options' => 'array',
        'stats' => 'array',
        'errors' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function changes()
    {
        return $this->hasMany(CompanyImportChange::class, 'batch_id');
    }

    public function companies()
    {
        return $this->hasMany(Company::class, 'import_batch_id');
    }
}
