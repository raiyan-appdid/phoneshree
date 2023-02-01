<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $table = "areas";
    protected $guarded = [];

    public function scopeIndia($q)
    {
        return $q->where('country_id', '101');
    }

    public function city()
    {
        return $this->hasMany(TruckOwner::class);
    }
}
