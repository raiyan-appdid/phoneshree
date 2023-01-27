<?php
namespace App\Http\Controllers\Admin;

use App\DataTables\MembershipDataTable;
use App\Http\Controllers\Controller;
use App\Models\FreeTrialPeriod;
use App\Models\Membership;
use App\Models\MembershipTransaction;
use App\Models\ReferScheme;
use App\Models\Seller;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MembershipController extends Controller
{
    public function index(MembershipDataTable $table)
    {
        $seller = Seller::all();
        $membership = Membership::all();
        $freeTrial = FreeTrialPeriod::where('id', 1)->first();
        $pageConfigs = ['has_table' => true];
        // $table->with('id', 1);
        return $table->render('content.tables.memberships', compact('pageConfigs', 'freeTrial', 'seller', 'membership'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'validity' => 'required',
            'amount' => 'required',
        ]);
        $data = new Membership;
        $data->name = $request->name;
        $data->validity = $request->validity;
        $data->amount = $request->amount;
        $data->save();
        return response([
            'header' => 'Added!',
            'message' => 'Membership Added successfully',
            'table' => 'membership-table',
        ]);
    }
    public function edit($id)
    {
        $name = Membership::findOrFail($id);
        return response($name);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'validity' => 'required',
            'amount' => 'required',
        ]);
        $data = Membership::findOrFail($request->id);
        $data->name = $request->name;
        $data->validity = $request->validity;
        $data->amount = $request->amount;
        $data->save();
        return response([
            'header' => 'Updated!',
            'message' => 'Membership Updated successfully',
            'table' => 'membership-table',
        ]);
    }

    public function status(Request $request)
    {
        $request->validate([
            'id' => 'required|numeric|exists:memberships,id',
            'status' => 'required|in:active,blocked',
        ]);

        Membership::findOrFail($request->id)->update(['status' => $request->status]);

        return response([
            'message' => 'membership status updated successfully',
            'table' => 'membership-table',
        ]);
    }

    public function destroy($id)
    {
        Membership::findOrFail($id)->delete();
        return response([
            'header' => 'Deleted!',
            'message' => 'membership deleted successfully',
            'table' => 'membership-table',
        ]);
    }

    public function assignMembership(Request $request)
    {
        $request->validate([
            'seller_id' => 'required|exists:sellers,id',
            'membership_id' => 'required|exists:memberships,id',
        ]);

        //bonus
        $seller = Seller::where('id', $request->seller_id)->first();
        $membership = Membership::where('id', $request->membership_id)->first();
        //checking if the membership is for first time
        $checkInMembership = MembershipTransaction::where('seller_id', $request->seller_id)->first();
        if (!isset($checkInMembership)) {
            $transaction = new WalletTransaction;
            $transaction->seller_id = $seller->id;
            $transaction->type = "credit";
            $transaction->amount = $membership->amount;
            $transaction->remark = "welcome bonus";
            $transaction->previous_wallet_balance = $seller->current_wallet_balance;
            $transaction->updated_wallet_balance = $seller->current_wallet_balance + $membership->amount;
            $transaction->save();

            $seller->current_wallet_balance = $seller->current_wallet_balance + $membership->amount;
            $seller->save();
        }

        //membership expiry date update
        if (Carbon::parse($seller->membership_expiry_date) >= Carbon::today()) {
            $seller->membership_expiry_date = Carbon::parse($seller->membership_expiry_date)->addDays($membership->validity);
        } else {
            $seller->membership_expiry_date = Carbon::today()->addDays($membership->validity);
        }
        $seller->save();

        $membershipTransaction = new MembershipTransaction;
        $membershipTransaction->seller_id = $seller->id;
        $membershipTransaction->membership_id = $membership->id;
        $membershipTransaction->membership_name = $membership->name;
        $membershipTransaction->validity = $membership->validity;
        $membershipTransaction->amount = $membership->amount;
        // $membershipTransaction->transaction_id = $item->order_id;
        $membershipTransaction->purchase_date = now();
        $membershipTransaction->expiry_date = $seller->membership_expiry_date;
        $membershipTransaction->save();

        //referal scheme
        if (!isset($checkInMembership)) {
            $checkReferal = Seller::where('id', $request->seller_id)->first();
            if ($checkReferal->referred_by != "") {
                $refferedBySellerId = $checkReferal->referred_by;
                $referScheme = ReferScheme::first();
                //referred by person
                $seller = Seller::where('id', $refferedBySellerId)->first();
                $transaction = new WalletTransaction;
                $transaction->seller_id = $seller->id;
                $transaction->type = "credit";
                $transaction->amount = $referScheme->referred_by_reward_amount;
                $transaction->remark = "referral bonus";
                $transaction->previous_wallet_balance = $seller->current_wallet_balance;
                $transaction->updated_wallet_balance = $seller->current_wallet_balance + $referScheme->referred_by_reward_amount;
                $transaction->save();

                $seller->current_wallet_balance = $seller->current_wallet_balance + $referScheme->referred_by_reward_amount;
                $seller->save();

                //referred person
                $transaction = new WalletTransaction;
                $transaction->seller_id = $checkReferal->id;
                $transaction->type = "credit";
                $transaction->amount = $referScheme->referred_person_reward_amount;
                $transaction->remark = "referral bonus";
                $transaction->previous_wallet_balance = $seller->current_wallet_balance;
                $transaction->updated_wallet_balance = $seller->current_wallet_balance + $referScheme->referred_person_reward_amount;
                $transaction->save();
                $seller->current_wallet_balance = $seller->current_wallet_balance + $referScheme->referred_person_reward_amount;
                $seller->save();
            }
        }
        return response([
            'header' => 'Successfull',
            'message' => "Membership Updated",
        ]);
    }
}
