<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>Offline | Minimalist Studio</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

        body {
            font-family: 'Raleway', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .container {
            max-width: 360px;
            width: 100%;
            text-align: center;
        }

        .logo img {
            width: 200px;
            margin-bottom: 2rem;
        }

        .icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1.5rem;
            stroke: var(--clay);
            fill: none;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        h1 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: .75rem;
        }

        p {
            font-size: .85rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .btn {
            display: inline-block;
            padding: .85rem 2rem;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background .18s;
            text-decoration: none;
        }

        .btn:hover {
            background: var(--clay-dark);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <img src="/images/minimalist-logo.png" alt="Minimalist Studio">
        </div>

        <svg class="icon" viewBox="0 0 24 24">
            <line x1="1" y1="1" x2="23" y2="23" />
            <path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55" />
            <path d="M5 12.55a10.94 10.94 0 0 1 5.17-2.39" />
            <path d="M10.71 5.05A16 16 0 0 1 22.56 9" />
            <path d="M1.42 9a15.91 15.91 0 0 1 4.7-2.88" />
            <path d="M8.53 16.11a6 6 0 0 1 6.95 0" />
            <line x1="12" y1="20" x2="12.01" y2="20" />
        </svg>

        <h1>Tidak Ada Koneksi</h1>
        <p>Sepertinya kamu sedang offline. Periksa koneksi internet kamu dan coba lagi.</p>

        <button class="btn" onclick="window.location.reload()">Coba Lagi</button>
    </div>
</body>

</html>
