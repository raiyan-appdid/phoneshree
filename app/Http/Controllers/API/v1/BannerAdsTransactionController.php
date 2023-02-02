<?php

namespace App\Http\Controllers\API\v1;

use App\Helpers\FileUploader;
use App\Http\Controllers\Controller;
use App\Models\ActiveBannerAd;
use App\Models\BannerAdsTransaction;
use App\Models\Seller;
use App\Models\WalletTransaction;
use App\Services\ActiveBannerAdsService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BannerAdsTransactionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
            'seller_id' => 'required',
            'banner_image' => 'required',
            'amount' => 'required',
            'number_of_days' => 'required',
        ]);

        $checkTheBalanceInSeller = Seller::where('id', $request->seller_id)->first();
        if ($checkTheBalanceInSeller->current_wallet_balance >= $request->amount) {

            $data = new BannerAdsTransaction;
            $data->city_id = $request->city_id;
            $data->seller_id = $request->seller_id;
            $data->banner_image = FileUploader::uploadFile($request->banner_image, 'images/banner-image');
            $data->amount = $request->amount;
            $data->number_of_days = $request->number_of_days;
            $data->expiry_date = Carbon::now()->addDays($request->number_of_days);
            $data->save();
            ActiveBannerAdsService::storeActiveBannerAds($data);

            $walletTransations = new WalletTransaction;
            $walletTransations->seller_id = $request->seller_id;
            $walletTransations->type = "debit";
            $walletTransations->amount = $request->amount;
            $walletTransations->remark = "spent on banner ad #" . $data->id;
            $walletTransations->previous_wallet_balance = $checkTheBalanceInSeller->current_wallet_balance;
            $walletTransations->updated_wallet_balance = $checkTheBalanceInSeller->current_wallet_balance - $request->amount;
            $walletTransations->save();

            $checkTheBalanceInSeller->current_wallet_balance = $checkTheBalanceInSeller->current_wallet_balance - $request->amount;
            $checkTheBalanceInSeller->save();
            return response([
                'message' => 'Banner Ads Transaction Successfully Created',
                'bannerAdsId' => $data->id,
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

        $data = BannerAdsTransaction::where('seller_id', $request->seller_id)->get();
        return response([
            'transactionList' => $data,
            'currentDate' => Carbon::today()->format('d m Y'),
        ]);

    }

    public function imageUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:banner_ads_transactions,id',
            'image' => 'required',
        ]);
        $active = ActiveBannerAd::where('banner_ads_transaction_id', $request->id)->first();
        if (isset($active)) {
            $active->image = FileUploader::uploadFile($request->image, 'images/banner-image');
            $active->save();
        }
        $bannerTransaction = BannerAdsTransaction::where('id', $request->id)->first();
        $bannerTransaction->banner_image = $active->image;
        $bannerTransaction->save();
        return response([
            'message' => 'Updated',
        ]);
    }
}
