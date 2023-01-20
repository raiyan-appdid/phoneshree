<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Extra;
use App\Models\FreeTrialPeriod;
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
                'privacy_policy' => $request->privacy_policy,
                'terms_and_conditions' => $request->terms_and_conditions,
            ]);
        return response([
            'header' => 'Success',
            'message' => 'Extra Detais Updated',
        ]);
    }
}
