<?php
        namespace App\Http\Controllers\Admin;
        use Hash;
        use App\Models\MembershipTransaction;
        use Illuminate\Http\Request;
        use App\DataTables\MembershipTransactionDataTable;
        use App\Http\Controllers\Controller;
        
        class MembershipTransactionController extends Controller
        {
        public function index(MembershipTransactionDataTable $table)
        {
        $pageConfigs = ['has_table' => true,];
        // $table->with('id', 1);
        return $table->render('content.tables.membershiptransactions', compact('pageConfigs'));
        }
        public function store(Request $request)
        {
        
        }
        public function edit($id)
        {
        $name = MembershipTransaction::findOrFail($id);
        return response($name);
        }
        
        public function update(Request $request)
        {
        
        }
        
        public function status(Request $request)
        {
        $request->validate([
            'id' => 'required|numeric|exists:membershiptransactions,id',
            'status' => 'required|in:active,blocked',
        ]);
        
        MembershipTransaction::findOrFail($request->id)->update(['status' => $request->status]);
        
        return response([
            'message' => 'membershiptransaction status updated successfully',
            'table' => 'membershiptransaction-table',
        ]);
        }
        
        public function destroy($id)
        {
        MembershipTransaction::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'membershiptransaction deleted successfully',
            'table' => 'membershiptransaction-table',
        ]);
        }
        }
        