<?php

namespace App\Services;

use App\Models\ActiveBannerAd;
use App\Models\BannerAdsTransaction;
use Carbon\Carbon;

class ActiveBannerAdsService
{
    public static function storeActiveBannerAds()
    {
        ActiveBannerAd::truncate();
        $data = BannerAdsTransaction::all();
        foreach ($data as $item) {
            // dd(Carbon::parse($item->membership_expiry_date) >= Carbon::today());
            if (Carbon::parse($item->expiry_date) >= Carbon::today()) {
                $data = new ActiveBannerAd;
                $data->city_id = $item->city_id;
                $data->image = $item->banner_image;
                $data->banner_ads_transaction_id = $item->id;
                $data->expiry_date = $item->expiry_date;
                $data->save();
            }
        }
    }
}
