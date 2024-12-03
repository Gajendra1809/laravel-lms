<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <header>
        <h1 class="text-3xl font-bold m-4">Dashboard</h1>
    </header>
    <main class="flex items-center justify-center min-h-screen">
        @yield('content')
    </main>
    <footer style="text-align: center; margin-top: 100px">
        <p>&copy; {{ date('Y') }} Library Management System</p>
    </footer>
</body>
</html>
