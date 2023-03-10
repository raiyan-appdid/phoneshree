<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Extra;
use App\Models\FreeTrialPeriod;
use App\Models\Seller;
use App\Models\WalletTransaction;
use App\Models\WelcomeBonus;
use Illuminate\Http\Request;

class BasicController extends Controller
{
    public function storeFreeTrialPeriod(Request $request)
    {
        $request->validate([
            'free_trial_period' => 'required',
        ]);
        $data = FreeTrialPeriod::updateOrCreate(
            ['id' => 1],
            ['free_trial_period' => $request->free_trial_period],
        );
        return response([
            'header' => "Free Trial Period Updated",
        ]);
    }

    public function storeWelcomeBonus(Request $request)
    {
        $request->validate([
            'welcome_bonus' => 'required',
        ]);
        $data = WelcomeBonus::updateOrCreate(
            ['id' => 1],
            ['welcome_bonus' => $request->welcome_bonus],
        );
        return response([
            'header' => 'Welcome Bonus Updated',
        ]);
    }

    public function extrasPage()
    {
        $extrasData = Extra::first();
        return view('content.forms.extras', compact('extrasData'));
    }

    public function storeOrUpdate(Request $request)
    {
        Extra::updateOrCreate(
            ['id' => '1'],
            [
                'phone1' => $request->phone1,
                'phone2' => $request->phone2,
                'email' => $request->email,
                'buyer_phone_expiry' => $request->buyer_phone_expiry,
                'privacy_policy' => $request->privacy_policy,
                'terms_and_conditions' => $request->terms_and_conditions,
                'android_version' => $request->android_version,
                'android_force_update' => $request->android_force_update == 'on' ? "active" : 'inactive',
                'ios_force_update' => $request->ios_force_update == 'on' ? "active" : 'inactive',
                'maintenance' => $request->maintenance == 'on' ? "active" : 'inactive',
            ]);
        return response([
            'header' => 'Success',
            'message' => 'Extra Detais Updated',
        ]);
    }

    public function get_cities(Request $request)
    {
        $request->validate([
            'state_id' => 'required',
        ]);
        $cities = City::india()->where('state_id', $request->state_id)->orderBy('name', 'ASC')->get(['id', 'name']);
        return response($cities);
    }
    public function get_areas(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
        ]);
        $areas = Area::where('city_id', $request->city_id)->orderBy('name', 'ASC')->get();
        return response($areas);
    }

    public function walletIndex()
    {
        $sellerData = Seller::all();
        return view('content.pages.wallet', compact('sellerData'));
    }

    public function getWalletData(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        $data = Seller::where('id', $request->id)->first();
        return response($data);
    }

    public function storeWalletData(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'seller' => 'required',
            'type' => 'required',
        ]);
        $data = Seller::where('id', $request->seller)->first();
        $wallet = new WalletTransaction;
        $wallet->seller_id = $request->seller;
        $wallet->type = $request->type;
        $wallet->amount = $request->amount;
        $wallet->previous_wallet_balance = $data->current_wallet_balance;
        if ($request->type == "credit") {
            $wallet->remark = "credited by admin";
            $wallet->updated_wallet_balance = $data->current_wallet_balance + $request->amount;
            $data->current_wallet_balance = $data->current_wallet_balance + $request->amount;
        } else if ($request->type == "debit") {
            $wallet->remark = "debited by admin";
            $wallet->updated_wallet_balance = $data->current_wallet_balance - $request->amount;
            $data->current_wallet_balance = $data->current_wallet_balance - $request->amount;
        }
        $wallet->save();
        $data->save();

        return response([
            'header' => 'Updated',
            'message' => 'Merchant Wallet Updated',
        ]);
    }
}