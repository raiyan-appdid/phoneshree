<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\MembershipDataTable;
use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(MembershipDataTable $table)
    {
        $pageConfigs = ['has_table' => true];
        // $table->with('id', 1);
        return $table->render('content.tables.memberships', compact('pageConfigs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'validity' => 'required',
            'amount' => 'required',
        ]);
        $data = new Membership;
        $data->name = $request->name;
        $data->validity = $request->validity;
        $data->amount = $request->amount;
        $data->save();
        return response([
            'header' => 'Added!',
            'message' => 'Membership Added successfully',
            'table' => 'membership-table',
        ]);
    }
    public function edit($id)
    {
        $name = Membership::findOrFail($id);
        return response($name);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'validity' => 'required',
            'amount' => 'required',
        ]);
        $data = Membership::findOrFail($request->id);
        $data->name = $request->name;
        $data->validity = $request->validity;
        $data->amount = $request->amount;
        $data->save();
        return response([
            'header' => 'Updated!',
            'message' => 'Membership Updated successfully',
            'table' => 'membership-table',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:memberships,id',
            'status' => 'required|in:active,blocked',
        ]);

        Membership::findOrFail($request->id)->update(['status' => $request->status]);

        return response([
            'message' => 'membership status updated successfully',
            'table' => 'membership-table',
        ]);
    }

    public function destroy($id)
    {
        Membership::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'membership deleted successfully',
            'table' => 'membership-table',
        ]);
    }
}
