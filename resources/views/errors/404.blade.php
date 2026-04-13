<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>404 | Minimalist Studio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=Raleway:wght@200;300;400;500;600&display=swap"
        rel="stylesheet">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --clay: #A0522D;
            --clay-dark: #8B4513;
            --bg: #F2EFEB;
            --text: #3A2E28;
            --text-muted: #9A8C82;
            --border: #E0D8D0;
        }

        html, body {
            height: 100%;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }

        .card {
            width: 100%;
            max-width: 360px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            animation: fadeUp .55s ease both;
        }

        .logo {
            margin-bottom: 2rem;
        }

        .logo a {
            display: inline-block;
            transition: opacity .2s;
        }

        .logo a:hover {
            opacity: .75;
        }

        .logo img {
            width: 200px;
            height: auto;
            object-fit: contain;
        }

        /* Big 404 number */
        .error-number {
            font-family: 'Cormorant Garamond', serif;
            font-size: 7rem;
            font-weight: 300;
            color: var(--clay);
            line-height: 1;
            margin-bottom: .5rem;
            letter-spacing: -.02em;
        }

        .error-divider {
            width: 48px;
            height: 2px;
            background: var(--clay);
            border-radius: 2px;
            margin: 0 auto 1.25rem;
            opacity: .4;
        }

        .error-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text);
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-bottom: .6rem;
        }

        .error-desc {
            font-size: .8rem;
            color: var(--text-muted);
            line-height: 1.65;
            margin-bottom: 2rem;
            max-width: 280px;
        }

        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: .8rem 2rem;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .8rem;
            font-weight: 500;
            letter-spacing: .16em;
            text-transform: uppercase;
            text-decoration: none;
            box-shadow: 0 4px 18px rgba(160, 82, 45, .28);
            transition: background .18s, transform .18s, box-shadow .18s;
        }

        .btn-home:hover {
            background: var(--clay-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(160, 82, 45, .38);
        }

        .btn-home:active {
            transform: translateY(0);
        }

        .btn-home svg {
            width: 15px;
            height: 15px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Decorative yoga icon */
        .yoga-icon {
            margin-bottom: 1.5rem;
            opacity: .18;
        }

        .yoga-icon svg {
            width: 72px;
            height: 72px;
            stroke: var(--clay);
            fill: none;
            stroke-width: 1.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body>
    <div class="card">

        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="{{ asset('images/minimalist-logo.png') }}" alt="Minimalist Studio">
            </a>
        </div>

        {{-- Decorative person icon --}}
        <div class="yoga-icon">
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="4" r="2"/>
                <path d="M12 6v5"/>
                <path d="M8 9c0 0 1.5 2 4 2s4-2 4-2"/>
                <path d="M9 21l3-6 3 6"/>
                <path d="M6 14l3-3"/>
                <path d="M18 14l-3-3"/>
            </svg>
        </div>

        <div class="error-number">404</div>
        <div class="error-divider"></div>

        <div class="error-title">Halaman Tidak Ditemukan</div>
        <p class="error-desc">
            Halaman mungkin sudah dipindahkan atau dihapus.
        </p>

        <a href="{{ url('/') }}" class="btn-home">
            <svg viewBox="0 0 24 24">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Kembali ke Beranda
        </a>

    </div>
</body>

</html>
