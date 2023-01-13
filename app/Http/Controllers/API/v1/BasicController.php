<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\ActiveBannerAd;
use App\Models\BannerPricing;
use App\Models\City;
use App\Models\FeaturedProductPricing;
use App\Models\Seller;
use App\Models\State;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class BasicController extends Controller
{
    //states and cities
    public function get_states(Request $request)
    {
        $states = State::india()->orderBy('name', 'ASC')->get(['id', 'name']);
        return response($states, 200);
    }

    public function get_cities(Request $request)
    {
        $request->validate([
            'state_id' => 'required',
        ]);
        $cities = City::india()->where('state_id', $request->state_id)->orderBy('name', 'ASC')->get(['id', 'name']);
        return response($cities, 200);
    }

    public function checkReferralCode(Request $request)
    {
        $request->validate([
            'referral_code' => 'required',
        ]);
        $data = Seller::where('my_referral_code', $request->referral_code)->first();
        if (empty($data)) {
            return response([
                'message' => 'failed',
                'seller_id' => 0,
            ]);
        } else {
            return response([
                'message' => 'success',
                'seller_id' => $data->id,
            ]);
        }
    }

    public function getBannerPricing()
    {
        $data = BannerPricing::all();
        return response($data, 200);
    }
    public function getFeaturedProductPricing()
    {
        $data = FeaturedProductPricing::all();
        return response($data, 200);
    }

    public function getBalanceAndTransactionList(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
        ]);

        $balance = Seller::where('id', $request->seller_id)->first();
        $transactionList = WalletTransaction::where('seller_id', $request->seller_id)->get();
        return response([
            'balance' => $balance->current_wallet_balance,
            'transactionList' => $transactionList,
        ], 200);
    }

    public function getSellerFromActiveBannerList(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
        ]);
        $data = ActiveBannerAd::where('city_id', $request->city_id)->with(['bannerAdsTransaction.seller'])->get()->pluck('bannerAdsTransaction')->pluck('seller');
        return response([
            'SellerList' => $data,
        ]);
    }

    public function getMembershipList(Request $request)
    {

        
    }

}
