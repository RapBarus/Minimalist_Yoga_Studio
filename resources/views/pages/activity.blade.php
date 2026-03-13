<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aktivitas | Minimalist Studio</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f6f6f6;
        }

        .container {
            max-width: 420px;
            margin: auto;
            padding: 16px;
        }

        h3 {
            color: #9c4b2b;
            margin-bottom: 16px;
        }

        .card {
            background: #b9d7f0;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .btn {
            background: #9c4b2b;
            color: white;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            margin-top: 12px;
            font-size: 14px;
            width: 120px;
        }

        .navbar {
            display: flex;
            justify-content: space-around;
            background: white;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
            max-width: 420px;
            left: 50%;
            transform: translateX(-50%);
            border-top: 1px solid #ddd;
        }

        .navbar a {
            text-decoration: none;
            color: #9c4b2b;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Aktivitas Anda</h3>

    <div class="card">
        <strong>YOGA WITH NIMA</strong><br>
        Selasa 20 Maret 2026<br>
        ⏰ 09:00 - 11:00 WIB<br>
        Rp. 50.000

        <div class="btn">Receipt</div>
    </div>

    <h3>Riwayat Aktivitas</h3>

    <div class="card">
        <strong>YOGA WITH NIMA</strong><br>
        Selasa 20 Maret 2026<br>
        ⏰ 09:00 - 11:00 WIB<br>
        Rp. 50.000

        <div class="btn">Receipt</div>
    </div>
</div>

<div class="navbar">
    <a href="/home">Home</a>
    <a href="/activity">Aktivitas</a>
    <a href="#">Profile</a>
</div>

</body>
</html>
