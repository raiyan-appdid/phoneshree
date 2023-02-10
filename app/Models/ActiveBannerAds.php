<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActiveBannerAds extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function bannerAdsTransaction()
    {
        return $this->belongsTo(BannerAdsTransaction::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
