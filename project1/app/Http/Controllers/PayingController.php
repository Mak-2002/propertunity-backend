<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\RentContract;
use App\Models\RentPost;
use App\Models\SaleContract;
use App\Models\SalePost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayingController extends Controller
{
    public function pay( Request $request , $post )
    {
        if ($request->posttype == 'sale') {
            $post = SalePost::findOrFail($post);
            $payment = new SaleContract;
            $payment->payment_value = $post->price;
        } else if ($request->posttype == 'rent') {
            $post = RentPost::findOrFail($post);
            $payment = new RentContract;
            $payment->payment_value = $post->monthly_rent*$request->months;
        }
        $payment->payee_id = $post->user_id;
        $payment->post_id = $post->id;
        $payment->payer_id = Auth::user()->id;
        $payer = Auth::user();
        $payee = User::where('id', $post->user_id)->first();
        if ($payer->balance < $payment->payment_value) {
            return response()->json([
                'success' => false,
                'message' => 'not enough balance',
            ]);
        }

        $payee->balance += $payment->payment_value;

        if (!$payee->save()) {
            return response()->json([
                'success' => false,
                'message' => 'could not make payment',
            ]);
        }

        $payer->balance -= $payment->payment_value;
        if (!$payer->save()) {
        $payee->balance -= $payment->payment_value;
            return response()->json([
                'success' => false,
                'message' => 'could not make payment',
            ]);
        } else {
        $payment->save();
            return response()->json([
                'success' => true,
                'message' => 'payment approved',
            ]);
        }

    }
}
