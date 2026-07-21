<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    use BelongsToDirectory;

    public $timestamps = false;

    protected $fillable = [
        'path', 'ip_hash', 'user_agent_summary',
        'company_id', 'directory_id', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function directory()
    {
        return $this->belongsTo(Directory::class);
    }

    /**
     * Explicit per-directory scope — complement to the global BelongsToDirectory scope.
     */
    public function scopeDirectory($query, $directoryId)
    {
        return $query->where('directory_id', $directoryId);
    }

    /**
     * Scope for a date range.
     */
    public function scopeBetween($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}
