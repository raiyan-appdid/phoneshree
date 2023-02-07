<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\BrandDataTable;
use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(BrandDataTable $table)
    {
        $pageConfigs = ['has_table' => true];
        // $table->with('id', 1);
        return $table->render('content.tables.brands', compact('pageConfigs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'logo' => 'required',
        ]);
        $data = new Brand;
        $data->title = $request->title;
        $data->logo = FileUploader::uploadFile($request->logo, 'images/brand');
        $data->save();
        return response([
            'header' => 'Successful!',
            'message' => 'brand Added successfully',
            'table' => 'brand-table',
        ]);
    }
    public function edit($id)
    {
        $name = Brand::findOrFail($id);
        return response($name);
    }

    public function update(Request $request)
    {
        $data = Brand::findOrFail($request->id);
        $data->title = $request->title;
        if (isset($request->logo)) {
            $data->logo = FileUploader::uploadFile($request->logo, 'images/brand');
        }
        $data->save();
        return response([
            'header' => 'Updated!',
            'message' => 'brand Updated successfully',
            'table' => 'brand-table',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:brands,id',
            'status' => 'required|in:active,blocked',
        ]);

        Brand::findOrFail($request->id)->update(['status' => $request->status]);

        return response([
            'message' => 'brand status updated successfully',
            'table' => 'brand-table',
        ]);
    }

    public function destroy($id)
    {
        Brand::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'brand deleted successfully',
            'table' => 'brand-table',
        ]);
    }
}
