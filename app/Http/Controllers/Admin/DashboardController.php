<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActiveBannerAdsService;
use App\Services\FeaturedProductService;

class DashboardController extends Controller
{

    public function home()
    {
        ActiveBannerAdsService::activateBannerAds();
        FeaturedProductService::activateFeaturedProduct();
        return view('content.dashboard');
    }
}
