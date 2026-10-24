<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel Front Page')</title>
    @vite('resources/css/app.css')
</head>
<body>
    <!-- Navigasi terpusat di sini -->
    <nav style="margin-bottom: 20px; padding: 10px; background-color: #f3f4f6;">
        <a href="{{ route('home') }}">Home</a> | 
        <a href="{{ route('about') }}">About</a> | 
        <a href="{{ route('contact') }}">Contact</a>
    </nav>

    <!-- Area ini akan diisi oleh halaman-halaman lain -->
    <main style="padding: 10px;">
        @yield('content')
    </main>
</body>
</html>