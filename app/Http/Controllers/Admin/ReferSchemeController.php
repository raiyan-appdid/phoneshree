<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferScheme;
use App\Models\WelcomeBonus;
use Illuminate\Http\Request;

class ReferSchemeController extends Controller
{
    public function index()
    {
        $welcomeBonus = WelcomeBonus::where('id', 1)->first();
        $data = ReferScheme::where('id', 1)->first();
        return view('content.forms.refer-scheme', compact('data', 'welcomeBonus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'referred_person_reward_amount' => 'required',
            'referred_by_reward_amount' => 'required',
        ]);

        $data = ReferScheme::updateOrCreate(
            ['id' => 1],
            [
                'referred_person_reward_amount' => $request->referred_person_reward_amount,
                'referred_by_reward_amount' => $request->referred_by_reward_amount,
            ]
        );
        return response([
            'header' => 'Refer Scheme Updated',
        ]);
    }
}
