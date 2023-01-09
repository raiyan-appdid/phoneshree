<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\BannerPricingDataTable;
use App\Http\Controllers\Controller;
use App\Models\BannerPricing;
use Illuminate\Http\Request;

class BannerPricingController extends Controller
{
    public function index(BannerPricingDataTable $table)
    {
        $pageConfigs = ['has_table' => true];
        // $table->with('id', 1);
        return $table->render('content.tables.bannerpricings', compact('pageConfigs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'price' => 'required',
            'number_of_days' => 'required',
        ]);
        $data = new BannerPricing;
        $data->price = $request->price;
        $data->number_of_days = $request->number_of_days;
        $data->save();
        return response([
            'header' => 'Successfull',
            'message' => 'Banner Price Added',
            'table' => 'bannerpricing-table',
        ]);
    }
    public function edit($id)
    {
        $name = BannerPricing::findOrFail($id);
        return response($name);
    }

    public function update(Request $request)
    {
        $data = BannerPricing::findOrFail($request->id);
        $data->price = $request->price;
        $data->number_of_days = $request->number_of_days;
        $data->save();
        return response([
            'header' => 'Updated',
            'message' => 'Banner Price Updated',
            'table' => 'bannerpricing-table',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:banner_pricings,id',
            'status' => 'required|in:active,blocked',
        ]);

        BannerPricing::findOrFail($request->id)->update(['status' => $request->status]);

        return response([
            'message' => 'bannerpricing status updated successfully',
            'table' => 'bannerpricing-table',
        ]);
    }

    public function destroy($id)
    {
        BannerPricing::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'bannerpricing deleted successfully',
            'table' => 'bannerpricing-table',
        ]);
    }
}
