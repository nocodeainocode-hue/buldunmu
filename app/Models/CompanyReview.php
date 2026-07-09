<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;

class CompanyReview extends Model
{
    use BelongsToDirectory;

    protected $fillable = [
        'company_id',
        'directory_id',
        'name',
        'email',
        'rating',
        'comment',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}
