<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>Pembayaran Berhasil | Minimalist Studio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Raleway:wght@300;400;500;600;700&display=swap"
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
            --clay-pale: #F0E6DF;
            --bg: #F2EFEB;
            --bg-white: #FFFFFF;
            --text: #3A2E28;
            --text-muted: #9A8C82;
            --border: #E0D8D0;
            --success: #27AE60;
            --success-pale: #eafaf1;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background: #E8E4DF;
            color: var(--text);
            min-height: 100vh;
            display: flex;
            justify-content: center;
        }

        .page {
            max-width: 560px;
            width: 100%;
            min-height: 100vh;
            background: var(--bg);
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 40px rgba(0, 0, 0, .08);
            animation: fadeUp .45s ease both;
        }

        .content {
            flex: 1;
            padding: 40px 24px 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
        }

        /* Success icon */
        .success-icon {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: var(--success-pale);
            border: 2px solid #a9dfbf;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .success-icon svg {
            width: 36px;
            height: 36px;
            stroke: var(--success);
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .success-title {
            font-weight: 700;
            font-size: 1.3rem;
            color: var(--text);
            text-align: center;
        }

        .success-sub {
            font-size: .82rem;
            color: var(--text-muted);
            text-align: center;
        }

        /* Receipt card */
        .receipt-card {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 20px;
            width: 100%;
            overflow: hidden;
        }

        .receipt-header {
            background: var(--clay);
            padding: 16px 20px;
            color: #fff;
        }

        .receipt-header-title {
            font-weight: 700;
            font-size: .78rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            opacity: .8;
            margin-bottom: 4px;
        }

        .receipt-amount {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
        }

        .receipt-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, .2);
            border-radius: 20px;
            padding: 3px 12px;
            font-size: .72rem;
            font-weight: 600;
            margin-top: 8px;
        }

        .receipt-status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #5dde8a;
        }

        .receipt-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            gap: 16px;
        }

        .receipt-row:last-child {
            border-bottom: none;
        }

        .receipt-label {
            font-size: .78rem;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .receipt-value {
            font-size: .82rem;
            font-weight: 600;
            color: var(--text);
            text-align: right;
        }

        /* Bottom bar */
        .bottom-bar {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 560px;
            padding: 16px 24px 28px;
            background: var(--bg);
            border-top: 1px solid var(--border);
            box-shadow: 0 -4px 20px rgba(0, 0, 0, .07);
        }

        .btn-selesai {
            width: 100%;
            padding: .9rem;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Raleway', sans-serif;
            font-size: .88rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            cursor: pointer;
            text-decoration: none;
            display: block;
            text-align: center;
            box-shadow: 0 4px 18px rgba(160, 82, 45, .28);
            transition: background .18s;
        }

        .btn-selesai:hover {
            background: var(--clay-dark);
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="content">
            <div class="success-icon">
                <svg viewBox="0 0 24 24">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
            </div>

            <div>
                <div class="success-title">Pembayaran Berhasil</div>
                <div class="success-sub" style="margin-top:6px;">Terima kasih! Kelas Anda telah dikonfirmasi.</div>
            </div>

            <div class="receipt-card">
                <div class="receipt-header">
                    <div class="receipt-header-title">Total Pembayaran</div>
                    <div class="receipt-amount">IDR {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                    <div class="receipt-status">Success</div>
                </div>
                <div class="receipt-body">
                    <div class="receipt-row">
                        <span class="receipt-label">Nama Kelas</span>
                        <span class="receipt-value">{{ strtoupper($schedule->class_name) }}</span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Coach</span>
                        <span class="receipt-value">{{ $schedule->coach_name }}</span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Tanggal</span>
                        <span
                            class="receipt-value">{{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Waktu</span>
                        <span class="receipt-value">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} –
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB</span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Nama</span>
                        <span class="receipt-value">{{ $user->name }}</span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Pembayaran</span>
                        <span class="receipt-value">{{ $transaction->payment_channel ?? 'QRIS' }}</span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Waktu Pembayaran</span>
                        <span
                            class="receipt-value">{{ \Carbon\Carbon::parse($transaction->updated_at)->translatedFormat('d M Y, H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-bar">
            <a href="{{ route('activity') }}" class="btn-selesai">Selesai</a>
        </div>
    </div>
</body>

</html>
