<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
