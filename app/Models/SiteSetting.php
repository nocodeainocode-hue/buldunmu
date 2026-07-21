<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use BelongsToDirectory;
    protected $fillable = [
        'site_name', 'logo', 'favicon', 'phone', 'whatsapp',
        'email', 'address', 'homepage_title', 'homepage_subtitle',
        'meta_title', 'meta_description', 'show_membership_plans', 'directory_id',
    ];

    protected $casts = [
        'show_membership_plans' => 'boolean',
    ];

    public static function getSettings()
    {
        return static::first() ?? new static(['site_name' => config('app.name')]);
    }
}
