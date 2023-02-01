<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\ActiveBannerAdsDataTable;
use App\Http\Controllers\Controller;
use App\Models\ActiveBannerAds;
use Illuminate\Http\Request;

class ActiveBannerAdsController extends Controller
{
    public function index(ActiveBannerAdsDataTable $table)
    {
        $pageConfigs = ['has_table' => true];
        return $table->render('content.tables.activebanneradss', compact('pageConfigs'));
    }
    public function store(Request $request)
    {

    }
    public function edit($id)
    {
        $name = ActiveBannerAds::findOrFail($id);
        return response($name);
    }

    public function update(Request $request)
    {

    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:activebanneradss,id',
            'status' => 'required|in:active,blocked',
        ]);

        ActiveBannerAds::findOrFail($request->id)->update(['status' => $request->status]);

        return response([
            'message' => 'activebannerads status updated successfully',
            'table' => 'activebannerads-table',
        ]);
    }

    public function destroy($id)
    {
        ActiveBannerAds::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'activebannerads deleted successfully',
            'table' => 'activebannerads-table',
        ]);
    }
}
