<?php

namespace App\Http\Controllers\API\v1\Post;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function index()
    {
    }
    public function store(Request $request)
    {
        // return $request->all();
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'post_id' => 'required|exists:posts,id'
        ]);
        $favourite = Favourite::create([
            'user_id' => $request->user_id,
            'post_id' => $request->post_id
        ]);
        return response($favourite, 201);
    }
}
