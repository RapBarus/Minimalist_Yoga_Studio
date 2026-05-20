<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>@yield('title', 'Admin | Minimalist Studio')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Raleway:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="manifest" href="/manifest.json">
    <style>
        body {
            display: block;
            padding-bottom: 160px;
            background: #E8E4DF;
        }

        .admin-frame {
            position: relative;
            max-width: 560px;
            margin: 0 auto;
            min-height: 100vh;
            background: var(--bg);
            overflow-x: hidden;
        }

        .main {
            margin-left: 0;
            min-height: 100vh;
        }

        /* ── Top header ── */
        .admin-header {
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

        .admin-header-logo img {
            height: 110px;
            width: auto;
            object-fit: contain;
        }

        .admin-header-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-header-name {
            font-size: .72rem;
            color: var(--text-muted);
            font-weight: 500;
            text-align: right;
        }

        .admin-header-name strong {
            color: var(--text);
            display: block;
            font-size: .82rem;
        }

        .btn-header-logout {
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

        .btn-header-logout:hover {
            background: var(--danger);
            color: #fff;
        }

        .btn-header-logout svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ── Page title ── */
        .page-titlebar {
            padding: 16px 20px 0;
        }

        .page-titlebar-title {
            font-weight: 700;
            font-size: 1.05rem;
            color: var(--text);
        }

        .page-titlebar-sub {
            font-size: .73rem;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .content {
            padding: 18px 20px 80px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* ── Bottom navbar ── */
        .bottom-nav {
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

        .bottom-nav-item {
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

        .bottom-nav-item svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .bottom-nav-item.active {
            color: var(--clay);
        }

        .bottom-nav-item.active::before {
            content: '';
            position: absolute;
            top: 0;
            left: 20%;
            right: 20%;
            height: 2.5px;
            background: var(--clay);
            border-radius: 0 0 4px 4px;
        }

        .bottom-nav-item:hover {
            color: var(--clay);
        }

        /* ── Mobile ── */
        @media (max-width: 600px) {
            .admin-header-name {
                display: none;
            }
        }

        /* ── Desktop: constrain to frame ── */
        @media (min-width: 600px) {
            .bottom-nav {
                max-width: 560px;
                left: 50%;
                transform: translateX(-50%);
                border-radius: 16px 16px 0 0;
                border-left: 1.5px solid var(--border);
                border-right: 1.5px solid var(--border);
            }
        }
    </style>
    @stack('styles')
</head>

<body>

    <div class="admin-frame">

        {{-- Top header --}}
        <div class="admin-header">
            <div class="admin-header-logo">
                <img src="{{ asset('images/minimalist-logo-2.png') }}" alt="Minimalist Studio">
            </div>
            <div class="admin-header-right">
                <div class="admin-header-name">
                    Admin Panel
                    <strong>{{ Session::get('user_name') }}</strong>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-header-logout">
                        <svg viewBox="0 0 24 24">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        {{-- Main --}}
        <div class="main">
            <div class="page-titlebar">
                <div class="page-titlebar-title">@yield('page-title')</div>
                <div class="page-titlebar-sub">@yield('page-sub')</div>
            </div>

            <div style="padding: 0 20px; margin-top: 14px;">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error">{{ $errors->first() }}</div>
                @endif
            </div>

            @yield('content')
        </div>

        {{-- Bottom navbar --}}
        <nav class="bottom-nav">
            <a href="{{ route('admin.dashboard') }}"
                class="bottom-nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                Home
            </a>

            <a href="{{ route('admin.coaches') }}"
                class="bottom-nav-item {{ request()->routeIs('admin.coaches*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                Coach
            </a>

            <a href="{{ route('admin.customers') }}"
                class="bottom-nav-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                Pelanggan
            </a>

            <a href="{{ route('admin.keuangan') }}"
                class="bottom-nav-item {{ request()->routeIs('admin.keuangan*') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24">
                    <line x1="12" y1="1" x2="12" y2="23" />
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                </svg>
                Keuangan
            </a>
        </nav>

    </div>

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
