<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\NotificationDataTable;
use App\Http\Controllers\Controller;
use App\Jobs\SendNotificationJob;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(NotificationDataTable $table)
    {
        $pageConfigs = ['has_table' => true];
        // $table->with('id', 1);
        return $table->render('content.tables.notifications', compact('pageConfigs'));
    }
    public function store(Request $request)
    {
        $data = new Notification;
        $data->title = $request->title;
        $data->description = $request->description;
        $data->save();

        dispatch(new SendNotificationJob(
            title:$data->title,
            message:$data->description,
        ));

        return response([
            'header' => 'Successfull!',
            'message' => 'Notification Sent',
            'table' => 'notification-table',
        ]);
    }
    public function edit($id)
    {
        $name = Notification::findOrFail($id);
        return response($name);
    }

    public function update(Request $request)
    {

    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:notifications,id',
            'status' => 'required|in:active,blocked',
        ]);

        Notification::findOrFail($request->id)->update(['status' => $request->status]);

        return response([
            'message' => 'notification status updated successfully',
            'table' => 'notification-table',
        ]);
    }

    public function destroy($id)
    {
        Notification::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'notification deleted successfully',
            'table' => 'notification-table',
        ]);
    }
}
