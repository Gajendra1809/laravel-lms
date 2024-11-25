@extends('layouts.app')

@section('content')
<form id="login-form">
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <br>
    <button type="submit">Login</button>
</form>

<button id="subscribe-button" style="display: none">Subscribe</button>

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

    // Perform a POST request with the token
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
        window.location.href = '/subscribeform/'+ res.data.client_secret;
    } else {
        alert('Subscription failed. Please try again.');
    }
    } catch (error) {
        alert('An error occurred. Please try again.'+error);
    }
});

</script>
@endsection
