<?php

namespace App\Services;

use App\Models\ActiveBannerAd;
use App\Models\BannerAdsTransaction;
use App\Models\SellPhoneByUser;
use Carbon\Carbon;

class ActiveBannerAdsService
{
    public static function activateBannerAds()
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

    public static function storeActiveBannerAds($data)
    {
        if (Carbon::parse($data->expiry_date) >= Carbon::today()) {
            $addData = new ActiveBannerAd;
            $addData->city_id = $data->city_id;
            $addData->image = $data->banner_image;
            $addData->banner_ads_transaction_id = $data->id;
            $addData->expiry_date = $data->expiry_date;
            $addData->save();
        }
    }

    public static function deleteBuyerPhoneOnExpiry()
    {
        $data = SellPhoneByUser::all();
        foreach ($data as $item) {
            if ($item->expiry_date <= Carbon::today()) {
                $item->delete();
            }
        }
    }
}
