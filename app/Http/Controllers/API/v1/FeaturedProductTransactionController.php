<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\FeaturedProductTransaction;
use App\Models\Seller;
use App\Models\WalletTransaction;
use App\Services\FeaturedProductService;
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
        $checkTheBalanceInSeller = Seller::where('id', $request->seller_id)->first();
        if ($checkTheBalanceInSeller->current_wallet_balance >= $request->amount) {

            $data = new FeaturedProductTransaction;
            $data->city_id = $request->city_id;
            $data->seller_id = $request->seller_id;
            $data->product_id = $request->product_id;
            $data->amount = $request->amount;
            $data->number_of_days = $request->number_of_days;
            $data->expiry_date = Carbon::now()->addDays($request->number_of_days);
            $data->save();
            FeaturedProductService::storeFeaturedProduct($data);

            $walletTransations = new WalletTransaction;
            $walletTransations->seller_id = $request->seller_id;
            $walletTransations->type = "debit";
            $walletTransations->amount = $request->amount;
            $walletTransations->remark = "spent on featured product #" . $data->id;
            $walletTransations->previous_wallet_balance = $checkTheBalanceInSeller->current_wallet_balance;
            $walletTransations->updated_wallet_balance = $checkTheBalanceInSeller->current_wallet_balance - $request->amount;
            $walletTransations->save();

            $checkTheBalanceInSeller->current_wallet_balance = $checkTheBalanceInSeller->current_wallet_balance - $request->amount;
            $checkTheBalanceInSeller->save();
            return response([
                'message' => 'Featured Product Transaction Successfully Created',
                'featuredProductId' => $data->id,
                'data' => $data,
            ], 200);
        } else {
            return response([
                'message' => 'Not Enough Amount',
            ], 200);
        }

    }

    public function transactionList(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
        ]);

        $data = FeaturedProductTransaction::where('seller_id', $request->seller_id)->with(['product'])->get();

        // return Carbon::today()->format('d m Y : h:m');

        return response([
            'transactionList' => $data,
            'currentDate' => Carbon::today()->format('d m Y'),
        ]);

    }
}
