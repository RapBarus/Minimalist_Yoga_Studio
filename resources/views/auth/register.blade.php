<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>Register | Minimalist Studio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=Raleway:wght@200;300;400;500;600&display=swap"
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
            --bg-input: #FFFFFF;
            --text: #3A2E28;
            --text-muted: #9A8C82;
            --border: #E0D8D0;
            --error: #C0392B;
        }

        html,
        body {
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
            animation: fadeUp .55s ease both;
        }

        .logo {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .logo a {
            display: inline-block;
            transition: opacity .2s;
        }

        .logo a:hover {
            opacity: .75;
        }

        .logo img {
            width: 260px;
            height: auto;
            object-fit: contain;
        }

        .page-title {
            font-weight: 600;
            font-size: 1.6rem;
            color: var(--text);
            text-align: center;
            margin-bottom: .4rem;
        }

        .subtitle {
            text-align: center;
            font-size: .8rem;
            color: var(--text-muted);
            margin-bottom: 1.75rem;
        }

        .subtitle a {
            color: var(--clay);
            text-decoration: none;
            font-weight: 500;
        }

        .subtitle a:hover {
            text-decoration: underline;
        }

        .alert-error {
            background: #fdecea;
            color: var(--error);
            border: 1px solid #f5c6c2;
            padding: .7rem .9rem;
            border-radius: 8px;
            font-size: .78rem;
            margin-bottom: 1rem;
            text-align: center;
        }

        .field {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            font-size: .72rem;
            font-weight: 500;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: .4rem;
        }

        .input-wrap {
            position: relative;
        }

        input[type="text"],
        input[type="tel"],
        input[type="password"] {
            width: 100%;
            padding: .75rem 2.5rem .75rem .9rem;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            color: var(--text);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        input.is-error {
            border-color: var(--error);
        }

        input::placeholder {
            color: #C0B4AC;
        }

        input:focus {
            border-color: var(--clay);
            box-shadow: 0 0 0 3px rgba(160, 82, 45, .10);
        }

        /* Phone group */
        .phone-group {
            display: flex;
        }

        .country-code {
            padding: .75rem .9rem;
            background: var(--bg-input);
            border: 1.5px solid var(--border);
            border-right: none;
            border-radius: 10px 0 0 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            font-weight: 500;
            color: var(--text);
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .country-code::after {
            content: '';
            display: block;
            width: 1px;
            height: 16px;
            background: var(--border);
            margin-left: .5rem;
        }

        .phone-group input {
            border-radius: 0 10px 10px 0;
            padding-left: .75rem;
        }

        /* Criteria hints */
        .criteria {
            list-style: none;
            font-size: .7rem;
            color: var(--text-muted);
            background: #faf8f6;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 12px;
            margin-top: 6px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .criteria li {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .criteria li::before {
            content: '•';
            color: var(--clay);
            flex-shrink: 0;
        }

        /* Eye toggle */
        .eye-btn {
            position: absolute;
            right: .75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            color: var(--text-muted);
            display: flex;
            align-items: center;
        }

        .eye-btn svg {
            width: 18px;
            height: 18px;
        }

        .btn-submit {
            width: 100%;
            padding: .85rem;
            margin-top: .5rem;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            font-weight: 500;
            letter-spacing: .18em;
            text-transform: uppercase;
            cursor: pointer;
            box-shadow: 0 4px 18px rgba(160, 82, 45, .28);
            transition: background .18s, transform .18s, box-shadow .18s;
        }

        .btn-submit:hover {
            background: var(--clay-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(160, 82, 45, .38);
        }

        .btn-submit:active {
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

        @media (min-width: 640px) {
            .logo img {
                width: 280px;
            }
        }
    </style>
</head>

<body>

    <div class="card">

        <div class="logo">
            <a href="{{ route('welcome') }}">
                <img src="{{ asset('images/minimalist-logo.png') }}" alt="Minimalist Studio">
            </a>
        </div>

        <h1 class="page-title">Register</h1>
        <p class="subtitle">Sudah punya akun? <a href="{{ route('login') }}">Log In</a></p>

        @if ($errors->any())
            <div class="alert-error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf

            <div class="field">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <input type="text" id="username" name="username" placeholder="Masukan Username Anda"
                        value="{{ old('username') }}" autocomplete="username"
                        class="{{ $errors->has('username') ? 'is-error' : '' }}">
                </div>
                <ul class="criteria">
                    <li>Hanya huruf (a-z, A-Z), angka (0-9), dan underscore (_)</li>
                    <li>Tanpa spasi atau karakter spesial</li>
                    <li>Maksimal 50 karakter</li>
                </ul>
            </div>

            <div class="field">
                <label>Nomer HP</label>
                <div class="phone-group">
                    <div class="country-code">+62</div>
                    <input type="tel" name="phone" placeholder="81234567890" value="{{ old('phone') }}"
                        autocomplete="tel" class="{{ $errors->has('phone') ? 'is-error' : '' }}">
                </div>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <input type="password" id="password" name="password" placeholder="Masukan Password"
                        autocomplete="new-password" class="{{ $errors->has('password') ? 'is-error' : '' }}">
                    <button type="button" class="eye-btn" onclick="togglePassword('password', this)">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                <ul class="criteria">
                    <li>Minimal 6 karakter, maksimal 50 karakter</li>
                    <li>Harus mengandung minimal 1 huruf dan 1 angka</li>
                </ul>
            </div>

            <button type="submit" class="btn-submit">Buat Akun</button>
        </form>

    </div>

    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
            btn.querySelector('svg').style.opacity = input.type === 'text' ? '1' : '.5';
        }
    </script>

</body>

</html>
