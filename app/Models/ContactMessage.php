<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use BelongsToDirectory;
    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'status',
    ];
}
