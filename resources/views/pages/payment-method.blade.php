<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>Pilih Pembayaran | Minimalist Studio</title>
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
        }

        .topbar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 18px 12px;
            background: var(--bg);
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 1px solid var(--border);
        }

        .back-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: var(--text);
            display: flex;
            align-items: center;
        }

        .back-btn svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .topbar-title {
            font-weight: 600;
            font-size: .9rem;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .content {
            flex: 1;
            padding: 24px 18px 160px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .section-title {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        /* Order summary */
        .order-card {
            background: var(--clay);
            border-radius: 16px;
            padding: 18px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .order-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .order-title {
            font-weight: 700;
            font-size: .95rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-bottom: 6px;
        }

        .order-meta {
            font-size: .78rem;
            opacity: .85;
            margin-bottom: 12px;
        }

        .order-price {
            font-weight: 700;
            font-size: 1.2rem;
        }

        /* Payment methods */
        .methods-grid {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .method-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            cursor: pointer;
            transition: border-color .18s, background .18s;
            position: relative;
        }

        .method-item:hover {
            border-color: var(--clay);
            background: #faf8f6;
        }

        .method-item.selected {
            border-color: var(--clay);
            background: var(--clay-pale);
        }

        .method-item input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .method-radio {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid var(--border);
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color .18s;
        }

        .method-item.selected .method-radio {
            border-color: var(--clay);
            background: var(--clay);
        }

        .method-item.selected .method-radio::after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #fff;
        }

        .method-logo {
            width: 52px;
            height: 32px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .method-logo-text {
            width: 52px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-weight: 800;
            font-size: .75rem;
            letter-spacing: .05em;
            flex-shrink: 0;
        }

        .method-info {
            flex: 1;
        }

        .method-name {
            font-weight: 600;
            font-size: .85rem;
            color: var(--text);
        }

        .method-desc {
            font-size: .72rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .method-group-label {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 4px 0;
        }

        /* Pay bar */
        .pay-bar {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 560px;
            padding: 16px 18px 28px;
            background: var(--bg);
            border-top: 1px solid var(--border);
            box-shadow: 0 -4px 20px rgba(0, 0, 0, .07);
        }

        .pay-btn {
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
            box-shadow: 0 4px 18px rgba(160, 82, 45, .28);
            transition: background .18s, transform .18s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .pay-btn:hover {
            background: var(--clay-dark);
            transform: translateY(-1px);
        }

        .pay-btn:disabled {
            opacity: .5;
            cursor: not-allowed;
            transform: none;
        }

        .pay-divider {
            width: 1px;
            height: 18px;
            background: rgba(255, 255, 255, .35);
        }

        .alert-error {
            background: #fdecea;
            color: #C0392B;
            border: 1px solid #f5c6c2;
            border-radius: 10px;
            padding: .7rem .9rem;
            font-size: .78rem;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="topbar">
            <button class="back-btn" onclick="history.back()">
                <svg viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
            </button>
            <span class="topbar-title">Metode Pembayaran</span>
        </div>

        <div class="content">
            @if ($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            {{-- Order summary --}}
            <div class="section-title">Detail Pesanan</div>
            <div class="order-card">
                <div class="order-title">{{ $schedule->class_name }}</div>
                <div class="order-meta">
                    {{ $schedule->coach_name }} &nbsp;·&nbsp;
                    {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('d F Y') }}
                    &nbsp;·&nbsp;
                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                    WIB
                </div>
                <div class="order-price">Rp {{ number_format($schedule->price, 0, ',', '.') }}</div>
            </div>

            {{-- Payment methods --}}
            <div class="section-title">Pilih Metode Pembayaran</div>

            <form id="payment-form" action="{{ route('payment.method.process', $schedule->schedule_id) }}"
                method="POST">
                @csrf
                <input type="hidden" name="schedule_id" value="{{ $schedule->schedule_id }}">

                <div class="methods-grid">
                    <div class="method-group-label">E-Wallet</div>

                    @php
                        $methods = [
                            [
                                'id' => 'QRIS',
                                'name' => 'QRIS',
                                'desc' => 'Scan QR dari semua aplikasi',
                                'color' => '#E91E8C',
                                'textColor' => '#fff',
                            ],
                            [
                                'id' => 'GOPAY',
                                'name' => 'GoPay',
                                'desc' => 'Bayar dengan saldo GoPay',
                                'color' => '#00AED6',
                                'textColor' => '#fff',
                            ],
                            [
                                'id' => 'OVO',
                                'name' => 'OVO',
                                'desc' => 'Bayar dengan saldo OVO',
                                'color' => '#4C3494',
                                'textColor' => '#fff',
                            ],
                            [
                                'id' => 'DANA',
                                'name' => 'DANA',
                                'desc' => 'Bayar dengan saldo DANA',
                                'color' => '#118EEA',
                                'textColor' => '#fff',
                            ],
                            [
                                'id' => 'SHOPEEPAY',
                                'name' => 'ShopeePay',
                                'desc' => 'Bayar dengan saldo ShopeePay',
                                'color' => '#EE4D2D',
                                'textColor' => '#fff',
                            ],
                        ];
                    @endphp

                    @foreach ($methods as $method)
                        <label class="method-item" onclick="selectMethod(this)">
                            <input type="radio" name="payment_method" value="{{ $method['id'] }}">
                            <div class="method-radio"></div>
                            <div class="method-logo-text"
                                style="background:{{ $method['color'] }};color:{{ $method['textColor'] }};">
                                {{ $method['name'] }}
                            </div>
                            <div class="method-info">
                                <div class="method-name">{{ $method['name'] }}</div>
                                <div class="method-desc">{{ $method['desc'] }}</div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </form>
        </div>

        <div class="pay-bar">
            <button class="pay-btn" id="pay-btn" onclick="submitPayment()" disabled>
                <span>Bayar</span>
                <div class="pay-divider"></div>
                <span>Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
            </button>
        </div>
    </div>

    <script>
        function selectMethod(label) {
            document.querySelectorAll('.method-item').forEach(el => el.classList.remove('selected'));
            label.classList.add('selected');
            label.querySelector('input[type="radio"]').checked = true;
            document.getElementById('pay-btn').disabled = false;
        }

        function submitPayment() {
            const btn = document.getElementById('pay-btn');
            btn.disabled = true;
            btn.innerHTML = '<span>Memproses...</span>';
            document.getElementById('payment-form').submit();
        }
    </script>
</body>

</html>
