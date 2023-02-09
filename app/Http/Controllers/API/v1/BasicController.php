<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\ActiveBannerAd;
use App\Models\ActiveFeaturedProduct;
use App\Models\Area;
use App\Models\BannerPricing;
use App\Models\Brand;
use App\Models\City;
use App\Models\Extra;
use App\Models\FeaturedProductPricing;
use App\Models\Membership;
use App\Models\MembershipTransaction;
use App\Models\PopUp;
use App\Models\Product;
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

    public function get_areas(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
        ]);
        $areas = Area::where('city_id', $request->city_id)->orderBy('name', 'ASC')->get(['id', 'name']);
        return response($areas, 200);
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
        $activeBanner = ActiveBannerAd::where('city_id', $request->city_id)->where('status', 'active')->with(['bannerAdsTransaction.seller'])->inRandomOrder()
            ->limit(5)
            ->get();
        $featuredProduct = ActiveFeaturedProduct::where('city_id', $request->city_id)->where('status', 'active')->with(['product.productImage', 'brand'])->with(['product.document'])->with(['product.seller'])->inRandomOrder()
            ->limit(10)
            ->get();

        if ($request->area_id != 'null') {
            $sellerList = Seller::where('area_id', $request->area_id)->where('city_id', $request->city_id)->inRandomOrder()->simplePaginate(20);
        } else {
            $sellerList = Seller::where('city_id', $request->city_id)->inRandomOrder()->simplePaginate(20);
        }

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

        $data = Membership::where('status', 'active')->get();
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
        $data = PopUp::where('type', $request->type)->where('status', 'active')->inRandomOrder()->first();
        return response([
            'popup' => $data,
        ], 200);
    }

    public function search(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
            'title' => 'required',
        ]);
        if (is_numeric($request->area_id)) {
            $data = Seller::where('area_id', $request->area_id)->where(function ($q) use ($request) {
                $q->Where('address', 'like', '%' . $request->title . '%')->orWhere('name', 'like', '%' . $request->title . '%')->orWhere('shop_name', 'like', '%' . $request->title . '%');
            })->get();

            if (count($data) == 0) {
                $data = Product::where('status', 'livesell')->where('product_title', 'like', '%' . $request->title . '%')->with(['productImage', 'document', 'seller', 'brand'])->get();
            }

        } else {
            $data = Seller::where('city_id', $request->city_id
            )->where(function ($q) use ($request) {
                $q->Where('address', 'like', '%' . $request->title . '%')->orWhere('name', 'like', '%' . $request->title . '%')->orWhere('shop_name', 'like', '%' . $request->title . '%');
            })->get();

            if (count($data) == 0) {
                $data = Product::where('status', 'livesell')->where('product_title', 'like', '%' . $request->title . '%')->with(['productImage', 'document', 'seller', 'brand'])->get();
            }
        }
        return response([
            'message' => 'success',
            'data' => $data,
        ]);
    }

    public function brandList(Request $request)
    {
        $brand = Brand::all();
        return response([
            'data' => $brand,
        ], 200);
    }

    public function getProductsByBrand(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
        ]);
        $data = Product::where('status', 'livesell')->where('brand_id', $request->brand_id)->with(['productImage', 'document', 'seller', 'brand'])->get();
        return response([
            'success' => true,
            'data' => $data,
        ], 200);
    }

    public function getProductsByCity(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
        ]);
        $data = Seller::where('city_id', $request->city_id)->with(['product.productImage', 'product.document', 'product.brand'])->get()->pluck('product');
        return response([
            'success' => true,
            'data' => collect($data)->filter()->flatten()->all(),
        ]);
    }
}
