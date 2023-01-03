<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\Seller;
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
            $data->name = $request->name;
            $data->number = $request->number;
            $data->email = $request->email;
            $data->city_id = $request->city_id;
            $data->state_id = $request->state_id;
            $data->shop_name = $request->shop_name;
            $data->referred_by = $request->referred_by;
            $data->short_description = $request->short_description;
            $data->membership_expiry_date = Carbon::now()->addDays(7);
            if (isset($request->shop_image)) {
                $data->shop_image = FileUploader::uploadFile($request->shop_image, 'images/seller');
            } else {
                $data->shop_image = "N/A";
            }
            $data->address = $request->address;
            $data->save();
            return response('Seller Registered Successfully', 200);
        } else {
            return response("Seller is already registered", 200);
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
        $data = Seller::findOrFail($request->seller_id);
        return response($data, 200);
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
            // 'address' => 'required',
        ]);
        $data = Seller::findOrFail($request->seller_id);
        $data->name = $request->name;
        $data->number = $request->number;
        $data->email = $request->email;
        $data->city_id = $request->city_id;
        $data->state_id = $request->state_id;
        $data->shop_name = $request->shop_name;
        $data->short_description = $request->short_description;
        if (isset($request->shop_image)) {
            $data->shop_image = FileUploader::uploadFile($request->shop_image, 'images/seller');
        }
        $data->address = $request->address;
        $data->save();
        return response("Profile Updated Successfully", 200);
    }
}
