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
    <link rel="manifest" href="/manifest.json">

    @if (session('show_splash'))
        <style>
            .page {
                opacity: 0 !important;
            }
        </style>
    @endif
    @stack('styles')
</head>

<body>

    {{-- Login transition splash --}}
    @if (session('show_splash'))
        <style>
            .page {
                opacity: 0;
            }
        </style>
        <div id="splash"
            style="position:fixed;inset:0;display:flex;align-items:center;justify-content:center;z-index:9999;overflow:hidden;background:transparent;">
            <div id="splash-spinner"
                style="width:40px;height:40px;border:3px solid rgba(160,82,45,0.2);border-top-color:#A0522D;border-radius:50%;animation:spin 0.8s linear infinite;">
            </div>
            <div id="splash-circle"
                style="position:absolute;width:0;height:0;border-radius:50%;background:#A0522D;transition:none;"></div>
            <img src="{{ asset('images/minimalist-logo.png') }}" id="splash-logo"
                style="position:absolute;width:80px;height:80px;object-fit:contain;opacity:0;filter:brightness(0) invert(1);transition:none;z-index:1;">
        </div>

        <style>
            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }
        </style>

        <script>
            (function() {
                const splash = document.getElementById('splash');
                const circle = document.getElementById('splash-circle');
                const logo = document.getElementById('splash-logo');
                const spinner = document.getElementById('splash-spinner');

                setTimeout(() => {
                    spinner.style.transition = 'opacity 0.2s ease';
                    spinner.style.opacity = '0';
                    setTimeout(() => spinner.style.display = 'none', 200);

                    circle.style.transition =
                        'width 1.2s cubic-bezier(0.2,0,0.4,1), height 1.2s cubic-bezier(0.2,0,0.4,1)';
                    circle.style.width = '250vh';
                    circle.style.height = '250vh';
                }, 800);

                setTimeout(() => {
                    logo.style.transition = 'opacity 0.4s ease';
                    logo.style.opacity = '1';
                }, 1800);

                setTimeout(() => {
                    splash.style.transition = 'opacity 0.5s ease';
                    splash.style.opacity = '0';
                }, 2600);

                setTimeout(() => {
                    splash.style.display = 'none';
                    const page = document.querySelector('.page');
                    if (page) {
                        page.style.transition = 'opacity 0.1s ease';
                        page.style.setProperty('opacity', '1', 'important');
                    }
                }, 3200);
            })();
            setTimeout(() => {
                const page = document.querySelector('.page');
                if (page) page.style.setProperty('opacity', '1', 'important');
            }, 4000);
        </script>
    @endif

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

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW registered'))
                    .catch(err => console.log('SW failed:', err));
            });
        }
    </script>

    @stack('scripts')

</body>



</html>
