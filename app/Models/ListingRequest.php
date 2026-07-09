<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;

class ListingRequest extends Model
{
    use BelongsToDirectory;
    protected $fillable = [
        'company_name', 'contact_name', 'phone', 'whatsapp',
        'email', 'website', 'category_id', 'city_id', 'district_id',
        'message', 'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
