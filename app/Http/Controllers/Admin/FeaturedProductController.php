<?php
        namespace App\Http\Controllers\Admin;
        use Hash;
        use App\Models\FeaturedProduct;
        use Illuminate\Http\Request;
        use App\DataTables\FeaturedProductDataTable;
        use App\Http\Controllers\Controller;
        
        class FeaturedProductController extends Controller
        {
        public function index(FeaturedProductDataTable $table)
        {
        $pageConfigs = ['has_table' => true,];
        // $table->with('id', 1);
        return $table->render('content.tables.featuredproducts', compact('pageConfigs'));
        }
        public function store(Request $request)
        {
        
        }
        public function edit($id)
        {
        $name = FeaturedProduct::findOrFail($id);
        return response($name);
        }
        
        public function update(Request $request)
        {
        
        }
        
        public function status(Request $request)
        {
        $request->validate([
            'id' => 'required|numeric|exists:featuredproducts,id',
            'status' => 'required|in:active,blocked',
        ]);
        
        FeaturedProduct::findOrFail($request->id)->update(['status' => $request->status]);
        
        return response([
            'message' => 'featuredproduct status updated successfully',
            'table' => 'featuredproduct-table',
        ]);
        }
        
        public function destroy($id)
        {
        FeaturedProduct::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'featuredproduct deleted successfully',
            'table' => 'featuredproduct-table',
        ]);
        }
        }
        