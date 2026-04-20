<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>@yield('title', 'Coach | Minimalist Studio')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Raleway:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
            height: 44px;
            width: auto;
            object-fit: contain;
        }

        .coach-header-right {
            font-size: .72rem;
            color: var(--text-muted);
            text-align: right;
        }

        .coach-header-right strong {
            display: block;
            color: var(--text);
            font-size: .82rem;
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
            <div class="coach-header-right">
                Coach
                <strong>{{ Session::get('user_name') }}</strong>
            </div>
        </div>

        {{-- Alerts --}}
        @if (session('success'))
            <div style="padding:14px 20px 0;">
                <div class="alert alert-success">{{ session('success') }}</div>
            </div>
        @endif
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
            Home
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

    @stack('scripts')
</body>

</html>
