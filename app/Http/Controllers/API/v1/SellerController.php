<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\FreeTrialPeriod;
use App\Models\Seller;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function sellerRegister(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'number' => 'required|unique:sellers,number',
            'email' => 'required',
            'shop_name' => 'required',
            'short_description' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
        ]);

        $returnValue = $this->CheckIFTheSellerIsAlreadyRegistered($request->number);
        if ($returnValue == "empty") {
            $data = new Seller;

            $bytes = random_bytes(3);
            $myRefferedCode = strtoupper(bin2hex($bytes));

            $checkReferredCode = Seller::where('my_referral_code', $myRefferedCode)->first();
            if (empty($checkReferredCode)) {
                $data->my_referral_code = $myRefferedCode;
            } else {
                $bytes = random_bytes(3);
                $myRefferedCode = strtoupper(bin2hex($bytes));
                $data->my_referral_code = $myRefferedCode;
            }
            $cityData = $this->citySelectOrAdd($request->city_id, $request->state_id);
            if (isset($request->area_id)) {
                $areaData = $this->areaSelectOrAdd($request->area_id, $request->city_id);
                $data->area_id = $areaData;
            }
            $data->name = $request->name;
            $data->number = $request->number;
            $data->email = $request->email;
            $data->city_id = $cityData;
            $data->state_id = $request->state_id;
            $data->shop_name = $request->shop_name;
            $data->referred_by = $request->referred_by;
            $data->gst_no = $request->gst_no;
            $data->short_description = $request->short_description;

            //free trail period data
            $freeTrailPeriod = FreeTrialPeriod::first();
            $data->membership_expiry_date = Carbon::now()->addDays($freeTrailPeriod->free_trial_period);
            if (isset($request->shop_image)) {
                $data->shop_image = FileUploader::uploadFile($request->shop_image, 'images/seller');
            } else {
                $data->shop_image = "N/A";
            }
            $data->address = $request->address;
            $data->save();
            return response([
                'message' => "Seller Registered Successfully",
                'seller_id' => $data->id,
            ], 200);
        } else {
            return response("Seller is already registered", 200);
        }
    }

    public function citySelectOrAdd($city, $state)
    {
        if (is_numeric($city)) {
            return $city;
        } else {
            $stateData = State::where('id', $state)->first();
            $addCity = new City;
            $addCity->name = $city;
            $addCity->state_id = $stateData->id;
            $addCity->state_code = $stateData->iso2;
            $addCity->country_id = $stateData->country_id;
            $addCity->country_code = $stateData->country_code;
            $addCity->flag = $stateData->flag;
            $addCity->save();
            return $addCity->id;
        }
    }
    public function areaSelectOrAdd($area, $city)
    {
        if (is_numeric($area)) {
            return $area;
        } else {
            $cityData = City::where('id', $city)->first();
            $addCity = new Area;
            $addCity->name = $area;
            $addCity->city_id = $cityData->id;
            $addCity->save();
            return $addCity->id;
        }
    }

    public function sellerLogIn(Request $request)
    {
        $request->validate([
            'number' => 'required',
        ]);
        $returnValue = $this->CheckIFTheSellerIsAlreadyRegistered($request->number);
        if ($returnValue != "empty") {
            return response([
                'message' => 'old',
                'id' => $returnValue->id,
            ], 200);
        } else {
            return response([
                'message' => 'new',
            ], 200);
        }
    }

    public function CheckIFTheSellerIsAlreadyRegistered($number)
    {
        $data = Seller::where('number', $number)->first();
        if (empty($data)) {
            return "empty";
        } else {
            return $data;
        }
    }

    public function sellerDetails(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
        ]);
        $data = Seller::where('id', $request->seller_id)->with(['city', 'state', 'product' => fn($q) => $q->with(['productImage', 'document'])->live()])->first();
        $myReferralCode = $data->my_referral_code;
        return response([
            'sellerDetail' => $data,
            'referral_share_message' => "🎉GOOD NEWS 😃🎉
            Please download Phone Shree App from Google play store. Use My referral code - '" . $myReferralCode . "'  while Signup to get Rs. 500  Instant Referral Bonus in your wallet.
            Android App : https://bit.ly/3Jb6r0v",
        ], 200);
    }

    public function sellerEdit(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
            // 'number' => 'required',
            // 'email' => 'required',
            // 'shop_name' => 'required',
            // 'short_description' => 'required',
            // 'shop_image' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
        ]);
        $data = Seller::findOrFail($request->seller_id);
        $cityData = $this->citySelectOrAdd($request->city_id, $request->state_id);
        $data->name = $request->name;
        $data->number = $request->number;
        $data->email = $request->email;
        $data->city_id = $cityData;
        $data->state_id = $request->state_id;
        $data->shop_name = $request->shop_name;
        $data->gst_no = $request->gst_no;
        $data->short_description = $request->short_description;
        if (isset($request->shop_image)) {
            $data->shop_image = FileUploader::uploadFile($request->shop_image, 'images/seller');
        }
        $data->address = $request->address;
        $data->save();
        return response("Profile Updated Successfully", 200);
    }
}
