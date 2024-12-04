<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Borrow;
use App\Enums\StatusEnum;

class PaymentService
{
    
    /**
     * Create a Stripe checkout session for the given borrow.
     *
     * @param int $borrow The borrow to create a checkout session for.
     * @return array The Stripe checkout session ID.
     */
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
