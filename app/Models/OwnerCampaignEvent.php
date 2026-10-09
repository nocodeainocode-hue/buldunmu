<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OwnerCampaignEvent extends Model
{
    public $timestamps = false;

    public const EVENTS = ['popup_shown', 'popup_dismiss', 'page_view', 'whatsapp_click'];
    public const SOURCES = ['popup', 'banner', 'button', 'direct'];

    protected $fillable = ['user_id', 'directory_id', 'event', 'source', 'created_at'];

    protected $casts = ['created_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
