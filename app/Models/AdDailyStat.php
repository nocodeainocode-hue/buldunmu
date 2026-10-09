<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdDailyStat extends Model
{
    public $timestamps = false;

    protected $fillable = ['ad_campaign_id', 'date', 'directory_id', 'impressions', 'clicks'];

}
