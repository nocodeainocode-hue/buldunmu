<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Firma kayıt sayfası için reklama özel başlık ve fayda metinleri (?v=anahtar). */
class RegisterVariant extends Model
{
    protected $fillable = ['key', 'name', 'badge', 'headline', 'subheadline', 'benefits', 'button_text', 'status'];

    protected $casts = [
        'benefits' => 'array',
    ];
}
