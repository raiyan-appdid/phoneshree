<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function productImage()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function document()
    {
        return $this->hasMany(Document::class);
    }

    public function scopeLive($query)
    {
        return $query->where('status', 'livesell');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
}
