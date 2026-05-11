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

        /* ── Hamburger ── */
        .btn-hamburger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: var(--clay-pale, #F0E6DF);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background .18s;
            flex-shrink: 0;
        }

        .btn-hamburger:hover {
            background: #e8d5c8;
        }

        .btn-hamburger svg {
            width: 18px;
            height: 18px;
            stroke: var(--clay);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ── Drawer overlay ── */
        .drawer-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .4);
            z-index: 150;
            animation: fadeIn .2s ease;
        }

        .drawer-overlay.open {
            display: block;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* ── Drawer ── */
        .drawer {
            position: absolute;
            top: 0;
            bottom: 0;
            right: -240px;
            width: 240px;
            height: 100%;
            min-height: 100vh;
            background: var(--bg-white);
            z-index: 160;
            display: flex;
            flex-direction: column;
            box-shadow: -8px 0 32px rgba(0, 0, 0, .12);
        }

        .drawer.open {
            right: 0%;
        }

        .drawer-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .drawer-header-title {
            font-weight: 700;
            font-size: .9rem;
            color: var(--text);
            letter-spacing: .02em;
        }

        .btn-drawer-close {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: var(--text-muted);
            border-radius: 6px;
            transition: background .15s;
        }

        .btn-drawer-close:hover {
            background: #f5f5f5;
        }

        .btn-drawer-close svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        .drawer-body {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .drawer-section-label {
            font-size: .62rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 12px 8px 6px;
        }

        .drawer-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 12px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--text);
            font-size: .82rem;
            font-weight: 500;
            transition: background .15s, color .15s;
        }

        .drawer-link:hover {
            background: var(--clay-pale, #F0E6DF);
            color: var(--clay);
        }

        .drawer-link.active {
            background: var(--clay);
            color: #fff;
        }

        .drawer-link svg {
            width: 17px;
            height: 17px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
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

        {{-- Drawer overlay --}}
        <div class="drawer-overlay" id="drawer-overlay" onclick="closeDrawer()"></div>

        {{-- Drawer --}}
        <div class="drawer" id="drawer">
            <div class="drawer-header">
                <div class="drawer-header-title">Menu</div>
                <button class="btn-drawer-close" onclick="closeDrawer()">
                    <svg viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
            <div class="drawer-body">
                <div class="drawer-section-label">Konten</div>

                <a href="{{ route('admin.membership') }}"
                    class="drawer-link {{ request()->routeIs('admin.membership*') ? 'active' : '' }}"
                    onclick="closeDrawer()">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 12V22H4V12" />
                        <path d="M22 7H2v5h20V7z" />
                        <path d="M12 22V7" />
                        <path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z" />
                        <path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z" />
                    </svg>
                    Membership
                </a>

                <a href="{{ route('admin.promos') }}"
                    class="drawer-link {{ request()->routeIs('admin.promos*') ? 'active' : '' }}"
                    onclick="closeDrawer()">
                    <svg viewBox="0 0 24 24">
                        <polygon
                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                    </svg>
                    Penawaran
                </a>

                <a href="{{ route('admin.classes') }}"
                    class="drawer-link {{ request()->routeIs('admin.classes*') ? 'active' : '' }}"
                    onclick="closeDrawer()">
                    <svg viewBox="0 0 24 24">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                    </svg>
                    Kelas
                </a>
            </div>
        </div>

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
                <button class="btn-hamburger" onclick="openDrawer()">
                    <svg viewBox="0 0 24 24">
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>
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
        function openDrawer() {
            document.getElementById('drawer').classList.add('open');
            document.getElementById('drawer-overlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeDrawer() {
            document.getElementById('drawer').classList.remove('open');
            document.getElementById('drawer-overlay').classList.remove('open');
            document.body.style.overflow = '';
        }
    </script>

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
