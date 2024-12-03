@extends('layouts.app')

@section('content')
<div class="container bg-white shadow-lg rounded-lg p-6 max-w-md w-full">
    <h2 class="text-2xl font-bold mb-4 text-gray-700 text-center">Payment Form</h2>
    <form id="payment-form" class="space-y-4">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <!-- Card Holder Name -->
        <div>
            <label for="card-holder-name" class="block text-sm font-medium text-gray-600 mb-1">
                Card Holder Name
            </label>
            <input id="card-holder-name" type="text" required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Enter card holder name">
        </div>

        <!-- Card Element -->
        <div id="card-element" class="p-3 border border-gray-300 rounded-md"></div>

        <!-- Subscribe Button -->
        <button type="submit" id="card-button" data-secret="{{ $intent }}"
            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
            Subscribe
        </button>
    </form>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const cardButton = document.getElementById('card-button');
    const clientSecret = cardButton.dataset.secret;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const token = localStorage.getItem('access_token');
        if (!token) {
            alert('Authentication token is missing. Please log in.');
            return;
        }
        const { setupIntent, error } = await stripe.confirmCardSetup(
            clientSecret, {
            payment_method: {
                card: cardElement,
                billing_details: {
                    name: document.getElementById('card-holder-name').value
                },
            },
        }
        );
        if (error) {
            console.error(error);
            alert('Payment setup failed. Please try again.');
        } else {
            try {
                const response = await fetch('http://127.0.0.1:8000/api/subscription/payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        payment_method: setupIntent.payment_method,
                    }),
                });

                const result = await response.json();
                console.log(result);
                if (response.ok) {
                    alert('Subscription successful!');
                    console.log(result.data);
                } else {
                    alert(result.message || 'Subscription failed. Please try again.');
                }
            } catch (err) {
                console.error('Error:', err);
                alert('An error occurred while processing your subscription.');
            }
        }
    });
</script>
@endsection