<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use BelongsToDirectory;

    public function allowsSharedDirectoryRecords(): bool
    {
        return false;
    }

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'message', 'status', 'directory_id',
    ];
}
