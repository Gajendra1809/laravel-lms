<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Borrow;
use App\Enums\StatusEnum;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use JsonResponseTrait;

    /**
     * Display the payment form view.
     *
     * @param int $borrowId
     * @return \Illuminate\Http\Response
     */
    public function showPaymentForm($borrowId){
        $borrow = Borrow::findOrFail($borrowId);
        return view('payment.payment-form', compact('borrow'));
    }

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

    /**
     * Retrieve a Stripe setup intent for the current user.
     *
     * The setup intent is used to setup a customer's payment method.
     * The intent is created if it doesn't already exist.
     *
     * @throws \Throwable
     * @return \Illuminate\Http\JsonResponse
     */
    public function showSubscriptionForm(){
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $user->createOrGetStripeCustomer();
            $intent = $user->createSetupIntent();
            return $this->successResponse($intent, 'Setup intent retrieved successfully', 200);
        } catch (\Throwable $th) {
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage());
        }
    }

    /**
     * Create a new subscription for the current user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createSubscription(Request $request){
        try {
            /** @var \App\Models\User $user */
            $user = auth()->user();
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($request->payment_method);
            $user->newSubscription('default', 'price_1QOw9dBdsatuffklyIZNean5')
                ->create($request->payment_method);

            return $this->successResponse(null, 'Subscription created successfully', 200);
        } catch (\Throwable $th) {
            return $this->errorResponse(config('msg.errors.something_wrong'), $th->getMessage());
        }
    }

}
