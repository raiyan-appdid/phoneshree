<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActiveBannerAd;
use App\Models\ActiveFeaturedProduct;
use App\Models\Product;
use App\Models\Seller;
use App\Models\SellPhoneByUser;

class DashboardController extends Controller
{

    public function home()
    {
        // ActiveBannerAdsService::activateBannerAds();
        // FeaturedProductService::activateFeaturedProduct();

        $sellerCount = Seller::all()->count();
        $productCount = Product::all()->count();
        $soldProductCount = Product::where('status', 'sold')->count();
        $liveProductCount = Product::where('status', 'livesell')->count();
        $inventoryProductCount = Product::where('status', 'inventory')->count();
        $featuredProductCount = ActiveFeaturedProduct::all()->count();
        $activeBannersCount = ActiveBannerAd::all()->count();
        $BuyerPhonesCount = SellPhoneByUser::all()->count();

        return view('content.dashboard', compact('sellerCount', 'productCount', 'soldProductCount', 'liveProductCount', 'inventoryProductCount', 'featuredProductCount', 'activeBannersCount', 'BuyerPhonesCount'));
    }
}
