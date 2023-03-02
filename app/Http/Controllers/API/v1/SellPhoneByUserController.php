<?php

namespace App\Http\Controllers\API\v1;

use App\DataTables\SellPhoneByUserDataTable;
use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\SellPhoneByUser;
use App\Models\SellPhoneByUserImage;
use Illuminate\Http\Request;

class SellPhoneByUserController extends Controller
{
    public function index(SellPhoneByUserDataTable $table)
    {
        $pageConfigs = ['has_table' => true];
        // $table->with('id', 1);
        return $table->render('content.tables.sellphonebyusers', compact('pageConfigs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'mobile' => 'required',
            'mobile_name' => 'required',
            'offer_price' => 'required',
            'image' => 'required',
        ]);
        $data = new SellPhoneByUser;
        $data->name = $request->name;
        $data->mobile = $request->mobile;
        $data->state = $request->state;
        $data->city_id = $request->city_id;
        $data->brand_id = $request->brand_id;
        $data->mobile_name = $request->mobile_name;
        $data->description = $request->description;
        $data->offer_price = $request->offer_price;
        $data->save();

        //storing multiple images
        if (isset($request->image)) {
            foreach ($request->product as $item) {
                $productImage = new SellPhoneByUserImage;
                $productImage->sell_phone_by_user_id = $data->id;
                $productImage->image = FileUploader::uploadFile($item, 'images/sellPhoneByUserImages');
                $productImage->save();
            }
        }

        return response([
            'success' => true,
        ], 200);
    }
    public function edit($id)
    {
        $name = SellPhoneByUser::findOrFail($id);
        return response($name);
    }

    public function update(Request $request)
    {
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:sell_phone_by_users,id',
            'status' => 'required|in:active,blocked',
        ]);

        SellPhoneByUser::findOrFail($request->id)->update(['status' => $request->status]);

        return response([
            'message' => 'sellphonebyuser status updated successfully',
            'table' => 'sellphonebyuser-table',
        ]);
    }

    public function destroy($id)
    {
        SellPhoneByUser::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'sellphonebyuser deleted successfully',
            'table' => 'sellphonebyuser-table',
        ]);
    }

    function list(Request $request) {

        if ($request->city_id != 'null') {
            $data = SellPhoneByUser::where('city_id', $request->city_id)->with(['sellPhoneByUserImage'])->get();
            return response([
                'success' => true,
                'data' => $data,
            ]);
        } elseif ($request->state_id != 'null') {
            $data = SellPhoneByUser::where('state_id', $request->city_id)->with(['sellPhoneByUserImage'])->get();
            return response([
                'success' => true,
                'data' => $data,
            ]);
        }
        $data = SellPhoneByUser::with(['sellPhoneByUserImage'])->all();
        return response([
            'data' => $data,
        ]);
    }
}
