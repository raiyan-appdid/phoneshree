<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedProduct extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'active_featured_products';

    public function featuredProductTransaction()
    {
        return $this->belongsTo(FeaturedProductTransaction::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
