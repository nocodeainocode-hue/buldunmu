<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use BelongsToDirectory;

    protected $fillable = [
        'directory_id', 'company_id', 'name',
        'total_directories', 'daily_limit',
        'start_date', 'end_date', 'status',
    ];

    public function allowsSharedDirectoryRecords(): bool
    {
        return false;
    }

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function items()
    {
        return $this->hasMany(CampaignItem::class);
    }
}
