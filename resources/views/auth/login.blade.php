<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Minimalist Studio</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 360px;
            padding: 24px;
            box-sizing: border-box;
        }

        .logo {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo img {
            width: 220px;
            max-width: 80%;
        }

        h2 {
            text-align: center;
            margin-bottom: 8px;
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            margin-bottom: 24px;
        }

        .subtitle a {
            color: #0d6efd;
            text-decoration: none;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            font-size: 14px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #9c4b2b;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src="{{ asset('images/minimalist-logo.png') }}" alt="Minimalist Studio">
    </div>

    <h2>Login</h2>
    <div class="subtitle">
        Belum punya akun? <a href="/register">Register</a>
    </div>

    <form>
        <input type="text" placeholder="Masukkan Username Anda">
        <input type="password" placeholder="Masukkan Password">
        <button class="btn" type="submit">Masuk</button>
    </form>
</div>

</body>
</html>
