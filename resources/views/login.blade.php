@extends('layouts.app')

@section('content')
<div class="bg-white shadow-lg rounded-lg p-6 max-w-md w-full">
    <h2 class="text-2xl font-bold mb-4 text-gray-700 text-center">Login</h2>
    <form id="login-form" class="space-y-4">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-600">Email:</label>
            <input type="email" id="email" name="email" required
                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Enter your email">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-600">Password:</label>
            <input type="password" id="password" name="password" required
                class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Enter your password">
        </div>
        <button type="submit"
            class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">
            Login
        </button>
    </form>
    <button id="subscribe-button" style="display: none"
        class="mt-4 w-full bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 focus:ring-2 focus:ring-green-400 focus:ring-opacity-50">
        Subscribe
    </button>
    
</div>

<script>
    document.getElementById('login-form').addEventListener('submit', async function (e) {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        try {
            const response = await fetch('http://127.0.0.1:8000/api/users/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password }),
            });
            const data = await response.json();
            if (response.ok) {
                localStorage.setItem('access_token', data.data.access_token);
                alert('Login successful!');
                console.log(localStorage.getItem('access_token'));
                document.getElementById('subscribe-button').style.display = 'block';
            } else {
                alert(data.error || 'Login failed!');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    document.getElementById('subscribe-button').addEventListener('click', async function (e) {
        e.preventDefault();
        try {
            let res = await fetch('http://127.0.0.1:8000/api/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('access_token'),
                },
            });
            res = await res.json();
            console.log(res);
            if (res.success) {
                alert('Subscription Page loaded successfully!');
                window.location.href = '/subscribeform/' + res.data.client_secret;
            } else {
                alert('Subscription failed. Please try again.');
            }
        } catch (error) {
            alert('An error occurred. Please try again.' + error);
        }
    });

</script>
@endsection