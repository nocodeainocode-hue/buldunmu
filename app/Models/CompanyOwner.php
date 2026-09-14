<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyOwner extends Model
{
    protected $fillable = [
        'company_id', 'user_id', 'directory_id', 'role', 'status', 'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function directory()
    {
        return $this->belongsTo(Directory::class);
    }
}
