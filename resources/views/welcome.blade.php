<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#F2EFEB">
    <title>Minimalist Studio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=Raleway:wght@200;300;400;500&display=swap"
        rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
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
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem 1.5rem;
        }

        .card {
            width: 100%;
            max-width: 340px;
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: fadeUp .6s ease both;
        }

        /* Logo */
        .logo img {
            width: 160px;
            height: 160px;
            object-fit: contain;
            margin-bottom: 3rem;
        }

        /* Buttons */
        .btn-group {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        .btn {
            display: block;
            width: 100%;
            padding: .9rem 1rem;
            border-radius: 10px;
            border: none;
            font-family: 'Raleway', sans-serif;
            font-size: .8rem;
            font-weight: 400;
            letter-spacing: .18em;
            text-transform: uppercase;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .btn-primary {
            background: var(--clay);
            color: #fff;
            box-shadow: 0 4px 18px rgba(160, 82, 45, .28);
        }

        .btn-primary:hover {
            background: var(--clay-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(160, 82, 45, .38);
        }

        .btn-secondary {
            background: transparent;
            color: var(--clay);
            border: 1.5px solid var(--clay);
        }

        .btn-secondary:hover {
            background: rgba(160, 82, 45, .06);
            transform: translateY(-1px);
        }

        .btn-primary:active,
        .btn-secondary:active {
            transform: translateY(0);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Desktop */
        @media (min-width: 640px) {
            .logo img {
                width: 180px;
                height: 180px;
            }

            .card {
                max-width: 360px;
            }
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="logo">
            <img src="{{ asset('images/minimalist-logo.png') }}" alt="Minimalist Studio">
        </div>

        <div class="btn-group">
            <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
            <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
        </div>
    </div>

</body>

</html>
