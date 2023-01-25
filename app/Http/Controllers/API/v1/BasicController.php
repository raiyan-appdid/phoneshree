<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\ActiveBannerAd;
use App\Models\ActiveFeaturedProduct;
use App\Models\BannerPricing;
use App\Models\City;
use App\Models\Extra;
use App\Models\FeaturedProductPricing;
use App\Models\Membership;
use App\Models\MembershipTransaction;
use App\Models\PopUp;
use App\Models\ReferScheme;
use App\Models\Seller;
use App\Models\State;
use App\Models\WalletTransaction;
use Carbon\Carbon;
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
        $transactionList = WalletTransaction::where('seller_id', $request->seller_id)->orderBy('created_at', 'desc')->get();
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

        if ($data->count() == 0) {
            return response([
                'message' => "no data found",
            ], 200);
        }

        // Check for multiple sellers
        $checkid = 0;
        foreach ($data as $dataitem) {
            if ($checkid != $dataitem->id) {
                $item[] = $dataitem;
            }
            $checkid = $dataitem->id;
        }
        return response([
            'SellerList' => $item,
        ]);
    }

    public function getBuyerDashboard(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
        ]);
        $activeBanner = ActiveBannerAd::where('city_id', $request->city_id)->with(['bannerAdsTransaction.seller'])->inRandomOrder()
            ->limit(5)
            ->get();
        $featuredProduct = ActiveFeaturedProduct::where('city_id', $request->city_id)->with(['product.productImage'])->with(['product.document'])->with(['product.seller'])->inRandomOrder()
            ->limit(10)
            ->get();
        $sellerList = Seller::where('city_id', $request->city_id)->simplePaginate(20);
        return response([
            'ActiveBanner' => $activeBanner,
            'FeaturedProduct' => $featuredProduct,
            'sellerList' => $sellerList,
        ], 200);
    }

    public function getMembershipList(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
        ]);
        $expiryDate = Seller::where('id', $request->seller_id)->first();

        if (Carbon::parse($expiryDate->membership_expiry_date) >= Carbon::today()) {
            $membershipStatus = "active";
        } else {
            $membershipStatus = "expired";
        }

        $data = Membership::all();
        return response([
            'membership' => $data,
            'expiryDate' => $expiryDate->membership_expiry_date,
            'currentDate' => Carbon::today()->format('Y-m-d'),
            'membershipStatus' => $membershipStatus,
        ]);
    }

    public function loadWallet(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
            'amount' => 'required',
        ]);
        $data = Seller::where('id', $request->seller_id)->first();

        $loadWallet = new WalletTransaction;
        $loadWallet->seller_id = $request->seller_id;
        $loadWallet->type = "credit";
        $loadWallet->amount = $request->amount;
        $loadWallet->remark = "added from bank";
        $loadWallet->previous_wallet_balance = $data->current_wallet_balance;
        $loadWallet->updated_wallet_balance = $data->current_wallet_balance + $request->amount;
        $loadWallet->save();

        $data->current_wallet_balance = $data->current_wallet_balance + $request->amount;
        $data->save();

        return response([
            'message' => 'Balance Updated Successfully',
        ], 200);

    }

    public function getMembershipTransactionList(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
        ]);

        $data = MembershipTransaction::where('seller_id', $request->seller_id)->get();
        return response([
            'membershipTransactionList' => $data,
        ], 200);
    }

    public function referralSchemeSetup()
    {
        $data = ReferScheme::first();
        return response([
            'referredBy' => $data->referred_by_reward_amount,
            'referredPerson' => $data->referred_person_reward_amount,
        ], 200);
    }

    public function extras()
    {
        $data = Extra::first();
        return response([
            'extras' => $data,
        ], 200);
    }

    public function getPopUp(Request $request)
    {
        $request->validate([
            'type' => 'required',
        ]);
        $data = PopUp::where('type', $request->type)->inRandomOrder()->first();
        return response([
            'popup' => $data,
        ], 200);
    }
}
