<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\SellerDataTable;
use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index(SellerDataTable $table)
    {
        $pageConfigs = ['has_table' => true];
        // $table->with('id', 1);
        return $table->render('content.tables.sellers', compact('pageConfigs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'number' => 'required',
            'email' => 'required',
            'shop_name' => 'required',
            'short_description' => 'required',
            // 'address' => 'required',
        ]);
        $data = new Seller;
        $data->name = $request->name;
        $data->number = $request->number;
        $data->email = $request->email;
        $data->shop_name = $request->shop_name;
        $data->short_description = $request->short_description;
        $data->membership_expiry_date = Carbon::now()->addDays(7);
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
        ]);
        $data = Seller::findOrFail($request->id);
        $data->name = $request->name;
        $data->number = $request->number;
        $data->email = $request->email;
        $data->shop_name = $request->shop_name;
        $data->short_description = $request->short_description;
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
