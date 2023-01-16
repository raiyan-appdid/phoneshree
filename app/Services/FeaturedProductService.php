<?php

namespace App\Services;

use App\Models\ActiveFeaturedProduct;
use App\Models\FeaturedProductTransaction;
use Carbon\Carbon;

class FeaturedProductService
{
    public static function activateFeaturedProduct()
    {
        ActiveFeaturedProduct::truncate();
        $data = FeaturedProductTransaction::all();
        foreach ($data as $item) {
            // dd(Carbon::parse($item->membership_expiry_date) >= Carbon::today());
            if (Carbon::parse($item->expiry_date) >= Carbon::today()) {
                $data = new ActiveFeaturedProduct;
                $data->city_id = $item->city_id;
                $data->product_id = $item->product_id;
                $data->featured_product_transaction_id = $item->id;
                $data->expiry_date = $item->expiry_date;
                $data->save();
            }
        }
    }

    public static function storeFeaturedProduct($data)
    {
        if (Carbon::parse($data->expiry_date) >= Carbon::today()) {

            $addActiveFeaturedProduct = new ActiveFeaturedProduct;
            $addActiveFeaturedProduct->city_id = $data->city_id;
            $addActiveFeaturedProduct->product_id = $data->product_id;
            $addActiveFeaturedProduct->featured_product_transaction_id = $data->id;
            $addActiveFeaturedProduct->expiry_date = $data->expiry_date;
            $addActiveFeaturedProduct->save();
        }
    }
}
