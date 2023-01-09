<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\FeaturedProductPricingDataTable;
use App\Http\Controllers\Controller;
use App\Models\FeaturedProductPricing;
use Illuminate\Http\Request;

class FeaturedProductPricingController extends Controller
{
    public function index(FeaturedProductPricingDataTable $table)
    {
        $pageConfigs = ['has_table' => true];
        // $table->with('id', 1);
        return $table->render('content.tables.featuredproductpricings', compact('pageConfigs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'price' => 'required',
            'number_of_days' => 'required',
        ]);
        $data = new FeaturedProductPricing;
        $data->price = $request->price;
        $data->number_of_days = $request->number_of_days;
        $data->save();
        return response([
            'header' => 'Successful',
            'message' => 'Featured Product Pricing Added',
            'table' => 'featuredproductpricing-table',
        ]);
    }
    public function edit($id)
    {
        $name = FeaturedProductPricing::findOrFail($id);
        return response($name);
    }

    public function update(Request $request)
    {
        $data = FeaturedProductPricing::findOrFail($request->id);
        $data->price = $request->price;
        $data->number_of_days = $request->number_of_days;
        $data->save();
        return response([
            'header' => 'Updated',
            'message' => 'Featured Product Pricing Updated',
            'table' => 'featuredproductpricing-table',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:featured_productpricings,id',
            'status' => 'required|in:active,blocked',
        ]);

        FeaturedProductPricing::findOrFail($request->id)->update(['status' => $request->status]);

        return response([
            'message' => 'featuredproductpricing status updated successfully',
            'table' => 'featuredproductpricing-table',
        ]);
    }

    public function destroy($id)
    {
        FeaturedProductPricing::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'featuredproductpricing deleted successfully',
            'table' => 'featuredproductpricing-table',
        ]);
    }
}
