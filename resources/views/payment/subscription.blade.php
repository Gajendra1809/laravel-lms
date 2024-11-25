@extends('layouts.app')

@section('content')
<div class="container">
    <form id="payment-form">
        @csrf
        <div>
            <label for="card-holder-name">Card Holder Name</label>
            <input id="card-holder-name" type="text" required>
        </div>
        <div id="card-element"></div>
        <button type="submit" id="card-button" data-secret="{{ $intent }}">
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

        const token = localStorage.getItem('access_token'); // Retrieve token from localStorage
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
            // Make an API call to the server
            try {
                const response = await fetch('http://127.0.0.1:8000/api/subscription/payment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}` // Include the token in the header
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
