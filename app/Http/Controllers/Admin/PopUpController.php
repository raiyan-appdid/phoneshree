<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\PopUpDataTable;
use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\PopUp;
use Illuminate\Http\Request;

class PopUpController extends Controller
{
    public function index(PopUpDataTable $table)
    {
        $pageConfigs = ['has_table' => true];
        // $table->with('id', 1);
        return $table->render('content.tables.popups', compact('pageConfigs'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required',
            'type' => 'required',
        ]);
        $data = new PopUp;
        if (isset($request->image)) {
            $data->image = FileUploader::uploadFile($request->image, 'images/pop-up');
        }
        $data->description = $request->description;
        $data->type = $request->type;
        $data->save();
        return response([
            'header' => 'Sucessful',
            'message' => 'New Pop Up Added',
            'table' => 'popup-table',
        ]);
    }
    public function edit($id)
    {
        $name = PopUp::findOrFail($id);
        return response($name);
    }

    public function update(Request $request)
    {
        $data = PopUp::findOrFail($request->id);
        if (isset($request->image)) {
            $data->image = FileUploader::uploadFile($request->image, 'images/pop-up');
        }
        $data->description = $request->description;
        $data->type = $request->type;
        $data->save();
        return response([
            'header' => 'Updated',
            'message' => 'Pop Up Updated',
            'table' => 'popup-table',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:pop_ups,id',
            'status' => 'required|in:active,blocked',
        ]);

        PopUp::findOrFail($request->id)->update(['status' => $request->status]);

        return response([
            'message' => 'popup status updated successfully',
            'table' => 'popup-table',
        ]);
    }

    public function destroy($id)
    {
        PopUp::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'popup deleted successfully',
            'table' => 'popup-table',
        ]);
    }
}
