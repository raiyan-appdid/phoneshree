<?php

namespace App\Services;

use App\Models\ActiveFeaturedProduct;
use App\Models\FeaturedProductTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FeaturedProductService
{
    public static function activateFeaturedProduct()
    {
        $activeFeaturedData = new ActiveFeaturedProduct;
        $featuredProducts = FeaturedProductTransaction::all();
        foreach ($featuredProducts as $item) {
            // dd(Carbon::parse($item->membership_expiry_date) >= Carbon::today());
            if (Carbon::parse($item->expiry_date) >= Carbon::today()) {
                $data[] = [
                    'city_id' => $item->city_id,
                    'product_id' => $item->product_id,
                    'featured_product_transaction_id' => $item->featured_product_transaction_id,
                    'expiry_date' => $item->expiry_date,
                ];
            }
        }
        // DB::beginTransaction();
        $activeFeaturedData::truncate();
        $activeFeaturedData::insert($data);
        // DB::commit();
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
