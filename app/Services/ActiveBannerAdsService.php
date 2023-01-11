<?php

namespace App\Services;

use App\Models\ActiveBannerAd;
use App\Models\BannerAdsTransaction;

class ActiveBannerAdsService
{
    public static function storeActiveBannerAds()
    {
        ActiveBannerAd::truncate();
        $data = BannerAdsTransaction::all();

    }
}
