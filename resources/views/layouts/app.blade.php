<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>@yield('title', 'Minimalist Studio')</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icon-32.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/icon-192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

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

    {{-- PWA Install Banner --}}
    <div id="pwa-install-banner"
        style="display:none;position:fixed;bottom:80px;left:50%;transform:translateX(-50%);z-index:9000;
               background:#fff;border:1px solid #e8e0d8;border-radius:16px;padding:14px 18px;
               box-shadow:0 4px 20px rgba(0,0,0,0.12);display:none;align-items:center;gap:12px;
               max-width:340px;width:calc(100% - 32px);">
        <img src="{{ asset('images/minimalist-logo.png') }}"
            style="width:40px;height:40px;object-fit:contain;border-radius:8px;">
        <div style="flex:1;">
            <div style="font-family:'Raleway',sans-serif;font-weight:600;font-size:13px;color:#2c2c2c;">Minimalist
                Studio</div>
            <div style="font-family:'Raleway',sans-serif;font-size:11px;color:#888;margin-top:1px;">Pasang aplikasi
                untuk akses lebih mudah</div>
        </div>
        <button id="pwa-install-btn"
            style="background:#A0522D;color:#fff;border:none;border-radius:8px;padding:7px 14px;
                   font-family:'Raleway',sans-serif;font-size:12px;font-weight:600;cursor:pointer;white-space:nowrap;">
            Pasang
        </button>
        <button id="pwa-dismiss-btn"
            style="background:none;border:none;cursor:pointer;padding:4px;color:#aaa;font-size:16px;line-height:1;">
            ✕
        </button>
    </div>

    <div class="page">

        {{-- Header --}}
        <div class="header">
            <img src="{{ asset('images/minimalist-logo-2.png') }}" alt="Minimalist Studio">
        </div>

        @yield('content')

    </div>

    <x-navbar />

    <script>
        // ── Service Worker Registration ──
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                const params = new URLSearchParams(window.location.search);
                const swParam = params.get('sw');
                if (swParam === 'cache-first' || swParam === 'network-first') {
                    localStorage.setItem('sw-strategy', swParam);
                }
                const strategy = localStorage.getItem('sw-strategy') || 'network-first';
                const swFile = strategy === 'cache-first' ? '/sw-cache-first.js' : '/sw-network-first.js';

                navigator.serviceWorker.getRegistrations().then((registrations) => {
                    const unregisterPromises = registrations
                        .filter(reg => reg.active && !reg.active.scriptURL.endsWith(swFile))
                        .map(reg => reg.unregister());

                    Promise.all(unregisterPromises).then(() => {
                        navigator.serviceWorker.register(swFile)
                            .then(reg => console.log('[SW] Registered:', swFile, reg))
                            .catch(err => console.warn('[SW] Registration failed:', err));
                    });
                });
            });
        }

        // ── PWA Install Prompt ──
        let deferredPrompt = null;
        const banner = document.getElementById('pwa-install-banner');
        const installBtn = document.getElementById('pwa-install-btn');
        const dismissBtn = document.getElementById('pwa-dismiss-btn');

        // Don't show if already dismissed or installed
        const dismissed = localStorage.getItem('pwa-install-dismissed');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            if (!dismissed) {
                setTimeout(() => {
                    banner.style.display = 'flex';
                }, 3000); // show after 3s so it doesn't feel intrusive
            }
        });

        installBtn && installBtn.addEventListener('click', async () => {
            if (!deferredPrompt) return;
            banner.style.display = 'none';
            deferredPrompt.prompt();
            const {
                outcome
            } = await deferredPrompt.userChoice;
            console.log('[PWA] Install outcome:', outcome);
            deferredPrompt = null;
        });

        dismissBtn && dismissBtn.addEventListener('click', () => {
            banner.style.display = 'none';
            localStorage.setItem('pwa-install-dismissed', '1');
        });

        // Hide banner if already installed (standalone mode)
        if (window.matchMedia('(display-mode: standalone)').matches) {
            if (banner) banner.style.display = 'none';
        }
    </script>

    @stack('scripts')

</body>

</html>
