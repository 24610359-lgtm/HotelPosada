<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\PaymentIntent;

class StripeController extends Controller
{
    public function intent(Request $request)
    {
        $request->validate([
            'amount' => 'nullable|integer|min:1',
        ]);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $intent = PaymentIntent::create([
            'amount' => $request->integer('amount', 1000),
            'currency' => 'mxn',
        ]);

        return response()->json(['clientSecret' => $intent->client_secret]);
    }
}
