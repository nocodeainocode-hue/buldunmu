<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignItem extends Model
{
    protected $fillable = [
        'campaign_id', 'directory_id', 'company_id',
        'slug', 'description', 'anchor_text', 'link_type',
        'scheduled_for', 'published_at', 'status', 'error_message',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'published_at' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function directory()
    {
        return $this->belongsTo(Directory::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
