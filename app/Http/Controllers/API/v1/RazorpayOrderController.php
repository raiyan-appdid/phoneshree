<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\MembershipTransaction;
use App\Models\RazorpayOrder;
use App\Models\ReferScheme;
use App\Models\Seller;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Razorpay\Api\Api;

class RazorpayOrderController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'seller_id' => 'required',
            'type' => 'required',
        ]);
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));
        $orderData = [
            'receipt' => 'phoneshree_' . uniqId(),
            'amount' => $request->amount * 100, //  rupees in paise
            'currency' => 'INR',
            'payment_capture' => 1, // auto capture
            'notes' => [
                "name" => "Raiyan",
                "contact" => "727867397",
            ],
        ];
        $razorpayOrder = $api->order->create($orderData);
        $razorpayOrderId = $razorpayOrder['id'];

        if ($request->type == "load_wallet") {
            //creating razorpay order by the razorapy orderid
            $razorpayOrder = new RazorpayOrder;
            $razorpayOrder->order_id = $razorpayOrderId;
            $razorpayOrder->seller_id = $request->seller_id;
            $razorpayOrder->amount = $request->amount;
            $razorpayOrder->type = $request->type;
            $razorpayOrder->save();
        }

        if ($request->type == "membership") {
            $request->validate([
                'membership_id' => 'required',
            ]);
            $membershipData = Membership::where('id', $request->membership_id)->first();

            if ($membershipData == '') {
                return response([
                    'message' => 'membership not found',
                ], 200);
            }

            //creating razorpay order by the razorapy orderid
            $razorpayOrder = new RazorpayOrder;
            $razorpayOrder->order_id = $razorpayOrderId;
            $razorpayOrder->seller_id = $request->seller_id;
            $razorpayOrder->amount = $request->amount;
            $razorpayOrder->type = $request->type;
            $razorpayOrder->membership_data = $membershipData;
            $razorpayOrder->save();
        }

        return response([
            'order_id' => $razorpayOrderId,
        ], 200);
    }

    public function fetch_order(Request $request)
    {
        $request->validate([
            'seller_id' => 'required',
        ]);

        $razorpayOrder = RazorpayOrder::where('seller_id', $request->seller_id)->where('status', 'created')->get();

        if (count($razorpayOrder) == 0) {
            return response([
                'message' => 'Seller Id not found',
            ], 200);
        }
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        foreach ($razorpayOrder as $item) {
            $payment = $api->order->fetch($item->order_id);
            if ($payment->status == 'paid') {
                if ($item->status == "created") {
                    if ($item->type == "load_wallet") {
                        $seller = Seller::where('id', $item->seller_id)->first();

                        $transaction = new WalletTransaction;
                        $transaction->seller_id = $item->seller_id;
                        $transaction->type = "credit";
                        $transaction->amount = $item->amount;
                        $transaction->remark = "added from bank";
                        $transaction->previous_wallet_balance = $seller->current_wallet_balance;
                        $transaction->updated_wallet_balance = $seller->current_wallet_balance + $item->amount;
                        $transaction->save();

                        $seller->current_wallet_balance = $seller->current_wallet_balance + $item->amount;
                        $seller->save();
                        $item->status = "inserted";
                        $item->save();
                    }

                    if ($item->type == "membership") {
                        //bonus
                        $seller = Seller::where('id', $item->seller_id)->first();
                        //checking if the membership is for first time
                        $checkInMembership = MembershipTransaction::where('seller_id', $item->seller_id)->first();
                        if (!isset($checkInMembership)) {
                            $transaction = new WalletTransaction;
                            $transaction->seller_id = $item->seller_id;
                            $transaction->type = "credit";
                            $transaction->amount = $item->amount;
                            $transaction->remark = "welcome bonus";
                            $transaction->previous_wallet_balance = $seller->current_wallet_balance;
                            $transaction->updated_wallet_balance = $seller->current_wallet_balance + $item->amount;
                            $transaction->save();

                            $seller->current_wallet_balance = $seller->current_wallet_balance + $item->amount;
                            $seller->save();
                        }
                        //membership expiry date update
                        $sellerUpdate = Seller::where('id', $seller->id)->first();
                        $orderData = RazorpayOrder::where('order_id', $item->order_id)->first();
                        $orderData->membership_data;
                        $jsonData = json_decode($orderData->membership_data);
                        if (Carbon::parse($sellerUpdate->membership_expiry_date) >= Carbon::today()) {
                            $sellerUpdate->membership_expiry_date = Carbon::parse($sellerUpdate->membership_expiry_date)->addDays($jsonData->validity);
                        } else {
                            $sellerUpdate->membership_expiry_date = Carbon::today()->addDays($jsonData->validity);
                        }
                        $sellerUpdate->save();

                        //membership transaction entry
                        $membershipTransaction = new MembershipTransaction;
                        $membershipTransaction->seller_id = $item->seller_id;
                        $membershipTransaction->membership_id = $jsonData->id;
                        $membershipTransaction->membership_name = $jsonData->name;
                        $membershipTransaction->validity = $jsonData->validity;
                        $membershipTransaction->amount = $jsonData->amount;
                        $membershipTransaction->transaction_id = $item->order_id;
                        $membershipTransaction->purchase_date = now();
                        $membershipTransaction->expiry_date = $sellerUpdate->membership_expiry_date;
                        $membershipTransaction->save();

                        $item->status = "inserted";
                        $item->save();

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

                                $item->status = "inserted";
                                $item->save();

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
                    }
                }
            }

        }

        return response([
            'message' => 'success',
        ], 200);
    }
}
