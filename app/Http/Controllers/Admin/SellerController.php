<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\SellerDataTable;
use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\FreeTrialPeriod;
use App\Models\Seller;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index(SellerDataTable $table)
    {
        $state = State::India()->get();
        $sellerData = Seller::all();
        $pageConfigs = ['has_table' => true];
        // $table->with('id', 1);
        return $table->render('content.tables.sellers', compact('pageConfigs', 'sellerData', 'state'));
    }
    public function store(Request $request)
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
                'header' => 'Added!',
                'message' => $request->name . 'Added successfully!',
                'table' => 'seller-table',
            ]);
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

    public function CheckIFTheSellerIsAlreadyRegistered($number)
    {
        $data = Seller::where('number', $number)->first();
        if (empty($data)) {
            return "empty";
        } else {
            return $data;
        }
    }

    public function edit($id)
    {
        $name = Seller::findOrFail($id);
        return response($name);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:sellers,id',
            'name' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
        ]);
        $data = Seller::findOrFail($request->id);
        $cityData = $this->citySelectOrAdd($request->city_id, $request->state_id);
        $data->name = $request->name;
        $data->number = $request->number;
        $data->city_id = $cityData;
        $data->state_id = $request->state_id;
        $data->email = $request->email;
        $data->shop_name = $request->shop_name;
        $data->short_description = $request->short_description;
        $data->gst_no = $request->gst_no;
        if (isset($request->shop_image)) {
            $data->shop_image = FileUploader::uploadFile($request->shop_image, 'images/seller');
        } else {
            $data->shop_image = "N/A";
        }
        $data->address = $request->address;
        $data->save();
        return response([
            'header' => 'Updated!',
            'message' => $request->name . 'Updated successfully!',
            'table' => 'seller-table',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:sellers,id',
            'status' => 'required|in:active,blocked',
        ]);

        Seller::findOrFail($request->id)->update(['status' => $request->status]);

        return response([
            'message' => 'seller status updated successfully',
            'table' => 'seller-table',
        ]);
    }

    public function destroy($id)
    {
        Seller::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'seller deleted successfully',
            'table' => 'seller-table',
        ]);
    }
}
