<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UsersDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(UsersDataTable $table)
    {
        $pageConfigs = ['has_table' => true];
        // $table->with('id', 1);
        return $table->render('content.tables.users', compact('pageConfigs'));
    }
    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        // $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->device_id = $request->device_id;
        // $user->address = $request->address;
        // $user->gender = $request->gender;
        $user->save();
        return response([
            'header' => 'Added!',
            'message' => $request->name . 'Added successfully!',
            'table' => 'users-table',
        ]);
    }
    public function edit($id)
    {
        $data = User::findOrFail($id);
        // dd($data);
        return response($data);
    }

    public function update(Request $request)
    {
        // return $request->all();
        $users = $request->validate([
            'id' => 'required|numeric|exists:users,id',
            'name' => 'required|min:3',
            // 'last_name' => 'required|min:3',
            // 'email' => '',
            'phone' => 'required|min:5',
            // 'address' => 'required',
            // 'gender' => 'required',
        ]);
        // return $users;
        $user = User::find($request->id);
        $user->name = $request->name;
        // $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->device_id = $request->device_id;
        // $user->address = $request->address;
        // $user->gender = $request->gender;
        $user->save();
        return response([
            'header' => 'Updated!',
            'message' => $request->name . 'Updated successfully!',
            'table' => 'users-table',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:users,id',
            'status' => 'required|in:active,blocked',
        ]);

        User::findOrFail($request->id)->update(['status' => $request->status]);

        return response([
            'message' => 'users status updated successfully',
            'table' => 'users-table',
        ]);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'users deleted successfully',
            'table' => 'users-table',
        ]);
    }
}