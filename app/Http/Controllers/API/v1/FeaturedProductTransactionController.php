<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\FeaturedProductTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FeaturedProductTransactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
            'seller_id' => 'required',
            'product_id' => 'required',
            'amount' => 'required',
            'number_of_days' => 'required',
        ]);
        $data = new FeaturedProductTransaction;
        $data->city_id = $request->city_id;
        $data->seller_id = $request->seller_id;
        $data->product_id = $request->product_id;
        $data->amount = $request->amount;
        $data->number_of_days = $request->number_of_days;
        $data->expiry_date = Carbon::now()->addDays($request->number_of_days);
        $data->save();
        return response([
            'message' => 'Featured Product Transaction Successfully Created',
            'data' => $data,
        ], 200);
    }
}
