<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>@yield('title', 'Coach | Minimalist Studio')</title>

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
    <style>
        body {
            background: #E8E4DF;
            min-height: 100vh;
        }

        .coach-frame {
            max-width: 560px;
            margin: 0 auto;
            min-height: 100vh;
            background: var(--bg);
            padding-bottom: 100px;
            box-shadow: 0 0 40px rgba(0, 0, 0, .08);
        }

        /* ── Header ── */
        .coach-header {
            background: var(--bg-white);
            border-bottom: 1px solid var(--border);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .coach-header-logo img {
            height: 110px;
            width: auto;
            object-fit: contain;
        }

        /* ── Bottom navbar ── */
        .coach-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--bg-white);
            border-top: 1.5px solid var(--border);
            display: flex;
            align-items: stretch;
            z-index: 100;
            height: 64px;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, .06);
        }

        .coach-nav-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: .6rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            transition: color .18s;
            position: relative;
            padding: 8px 4px;
        }

        .coach-nav-item svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .coach-nav-item.active {
            color: var(--clay);
        }

        .coach-nav-item.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 20%;
            right: 20%;
            height: 2.5px;
            background: var(--clay);
            border-radius: 0 0 4px 4px;
        }

        .coach-nav-item:hover {
            color: var(--clay);
        }

        @media (min-width: 600px) {
            body {
                display: flex;
                justify-content: center;
                align-items: flex-start;
                min-height: 100vh;
            }

            .coach-frame {
                max-width: 560px;
                width: 100%;
                min-height: 100vh;
                box-shadow: 0 0 40px rgba(0, 0, 0, .08);
            }

            .coach-nav {
                max-width: 560px;
                left: 50%;
                transform: translateX(-50%);
                border-radius: 16px 16px 0 0;
                border-left: 1.5px solid var(--border);
                border-right: 1.5px solid var(--border);
            }
        }

        /* ── Page content ── */
        .coach-content {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .coach-page-title {
            font-weight: 700;
            font-size: 1rem;
            color: var(--clay);
            letter-spacing: .04em;
            text-transform: uppercase;
            padding: 16px 20px 0;
        }

        .btn-coach-logout {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            background: transparent;
            border: 1.5px solid var(--danger);
            color: var(--danger);
            border-radius: 8px;
            font-family: 'Raleway', sans-serif;
            font-size: .72rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .18s, color .18s;
        }

        .btn-coach-logout:hover {
            background: var(--danger);
            color: #fff;
        }

        .btn-coach-logout svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ── Toast ── */
        .toast-container {
            position: fixed;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 300;
            display: flex;
            flex-direction: column;
            gap: 8px;
            max-width: 520px;
            width: calc(100% - 40px);
            animation: toastIn .3s ease both;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .12);
            font-size: .82rem;
            color: var(--text);
        }

        .toast-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: #eafaf1;
        }

        .toast-icon svg {
            width: 16px;
            height: 16px;
            stroke: #27AE60;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .toast-text {
            flex: 1;
            font-weight: 500;
            line-height: 1.4;
        }

        .toast-close {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .toast-close svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="coach-frame">

        {{-- Header --}}
        <div class="coach-header">
            <div class="coach-header-logo">
                <img src="{{ asset('images/minimalist-logo-2.png') }}" alt="Minimalist Studio">
            </div>
        </div>

        {{-- Toast: success --}}
        @if (session('success'))
            <div class="toast-container" id="toast-container">
                <div class="toast">
                    <div class="toast-icon">
                        <svg viewBox="0 0 24 24">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </div>
                    <div class="toast-text">{{ session('success') }}</div>
                    <button class="toast-close" onclick="this.closest('.toast-container').remove()">
                        <svg viewBox="0 0 24 24">
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        {{-- Error alert --}}
        @if ($errors->any())
            <div style="padding:14px 20px 0;">
                <div class="alert alert-error">{{ $errors->first() }}</div>
            </div>
        @endif

        @yield('content')

    </div>

    {{-- Bottom navbar --}}
    <nav class="coach-nav">
        <a href="{{ route('coach.dashboard') }}"
            class="coach-nav-item {{ request()->routeIs('coach.dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                <polyline points="9 22 9 12 15 12 15 22" />
            </svg>
            Beranda
        </a>

        <a href="{{ route('coach.profile') }}"
            class="coach-nav-item {{ request()->routeIs('coach.profile*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
            Profil
        </a>
    </nav>

    <script>
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

        // Auto-dismiss toast after 4s
        const toast = document.getElementById('toast-container');
        if (toast) {
            setTimeout(() => {
                toast.style.transition = 'opacity .3s ease';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
    </script>

    @stack('scripts')
</body>

</html>
