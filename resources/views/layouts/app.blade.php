<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>@yield('title', 'Minimalist Studio')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Raleway:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>

<body>

    <div class="page">

        {{-- Header --}}
        <div class="header">
            <img src="{{ asset('images/minimalist-logo-2.png') }}" alt="Minimalist Studio">
            <div class="header-greeting">
                Selamat datang
                <strong>{{ Session::get('user_name', 'Member') }}</strong>
            </div>
        </div>

        @yield('content')

    </div>

    <x-navbar />

    @stack('scripts')

</body>

</html>
