<?php
namespace App\Http\Controllers\Admin;

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
            'id' => 'required|numeric|exists:sellphonebyusers,id',
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
