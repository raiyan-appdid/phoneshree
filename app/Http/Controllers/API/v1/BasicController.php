<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\BannerPricing;
use App\Models\City;
use App\Models\FeaturedProductPricing;
use App\Models\Seller;
use App\Models\State;
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

}
