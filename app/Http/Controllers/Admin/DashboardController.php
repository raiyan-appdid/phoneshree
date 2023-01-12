<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActiveBannerAdsService;

class DashboardController extends Controller
{

    public function home()
    {
        ActiveBannerAdsService::storeActiveBannerAds();
        return view('content.dashboard');
    }
}
