<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ActiveBannerAdsService;

class DashboardController extends Controller
{

    public function home()
    {
        ActiveBannerAdsService::activateBannerAds();
        return view('content.dashboard');
    }
}
