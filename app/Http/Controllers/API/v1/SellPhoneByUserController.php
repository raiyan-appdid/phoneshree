<?php
namespace App\Http\Controllers\API\v1;

use App\DataTables\SellPhoneByUserDataTable;
use App\Http\Controllers\Controller;
use App\Models\SellPhoneByUser;
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
}