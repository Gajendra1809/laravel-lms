<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Borrow;
use App\Enums\StatusEnum;

class PaymentController extends Controller
{
    public function showPaymentForm($borrowId){
        $borrow = Borrow::findOrFail($borrowId);
        return view('payment.payment-form', compact('borrow'));
    }

    public function createCheckoutSession($borrow){
        Stripe::setApiKey(config('services.stripe.secret'));
        $borrow = Borrow::findOrFail($borrow);
        $amount = $borrow->late_fee;
        try {
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Late Fee Payment',
                        ],
                        'unit_amount' => $amount * 100, // Convert amount to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success'),
                'cancel_url' => route('payment.cancel'),
            ]);
            $borrow->update(['late_fee' => 0]);
            $borrow->update(['return_date' => now()]);
            $borrow->book->update(['available' => StatusEnum::AVAILABLE->value]);
            return ['id' => $checkout_session->id];
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

}
