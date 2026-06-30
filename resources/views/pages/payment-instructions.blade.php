<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>Instruksi Pembayaran | Minimalist Studio</title>
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
            padding: 24px 18px 140px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .section-title {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--text-muted);
            align-self: flex-start;
        }

        /* Method badge */
        .method-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: .82rem;
            color: #fff;
            margin-bottom: 4px;
        }

        /* QR container */
        .qr-container {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 20px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            width: 100%;
        }

        .qr-title {
            font-weight: 700;
            font-size: .9rem;
            color: var(--text);
            text-align: center;
        }

        .qr-img {
            width: 200px;
            height: 200px;
            border-radius: 12px;
        }

        .qr-amount {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--clay);
        }

        .qr-expiry {
            font-size: .75rem;
            color: var(--text-muted);
            text-align: center;
        }

        .timer {
            font-weight: 700;
            color: var(--clay);
            font-size: .85rem;
        }

        /* Deeplink button */
        .deeplink-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: .85rem;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: background .18s;
        }

        .deeplink-btn:hover {
            background: var(--clay-dark);
        }

        .deeplink-btn svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        /* Order summary */
        .summary-card {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 16px 18px;
            width: 100%;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: .82rem;
            color: var(--text-muted);
            padding: 6px 0;
        }

        .summary-row span:last-child {
            color: var(--text);
            font-weight: 500;
        }

        .summary-row.total {
            font-weight: 700;
            font-size: .95rem;
            color: var(--text);
            border-top: 1px solid var(--border);
            margin-top: 6px;
            padding-top: 12px;
        }

        .summary-row.total span:last-child {
            color: var(--clay);
        }

        /* Steps */
        .steps {
            background: var(--clay-pale);
            border-radius: 12px;
            padding: 16px;
            width: 100%;
        }

        .steps-title {
            font-weight: 700;
            font-size: .78rem;
            color: var(--clay);
            margin-bottom: 10px;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: .78rem;
            color: var(--text);
            margin-bottom: 8px;
        }

        .step:last-child {
            margin-bottom: 0;
        }

        .step-num {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--clay);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .65rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        /* Bottom bar */
        .bottom-bar {
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
            display: flex;
            gap: 10px;
        }

        .btn-check {
            flex: 1;
            padding: .85rem;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            letter-spacing: .08em;
            cursor: pointer;
            transition: background .18s;
        }

        .btn-check:hover {
            background: var(--clay-dark);
        }

        .btn-cancel {
            padding: .85rem 18px;
            background: transparent;
            color: var(--text-muted);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            font-weight: 500;
            cursor: pointer;
            transition: border-color .18s;
        }

        .btn-cancel:hover {
            border-color: var(--text-muted);
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
            <span class="topbar-title">Instruksi Pembayaran</span>
        </div>

        <div class="content">
            @php
                $methodColors = [
                    'QRIS' => '#E91E8C',
                    'GOPAY' => '#00AED6',
                    'OVO' => '#4C3494',
                    'DANA' => '#118EEA',
                    'SHOPEEPAY' => '#EE4D2D',
                ];
                $methodColor = $methodColors[$transaction->payment_channel] ?? '#A0522D';
            @endphp

            <div class="method-badge" style="background: {{ $methodColor }}">
                {{ $transaction->payment_channel }}
            </div>

            {{-- QR Code or Deeplink --}}
            @if ($qrCode)
                <div class="qr-container">
                    <div class="qr-title">Scan QR Code ini</div>
                    <img src="{{ $qrCode }}" alt="QR Code" class="qr-img">
                    <div class="qr-amount">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                    @if ($expiryTime)
                        <div class="qr-expiry">
                            Berlaku hingga: <span class="timer" id="timer">--:--</span>
                        </div>
                    @endif
                </div>

                <div class="steps">
                    <div class="steps-title">Cara Bayar</div>
                    <div class="step">
                        <div class="step-num">1</div><span>Buka aplikasi pembayaran kamu</span>
                    </div>
                    <div class="step">
                        <div class="step-num">2</div><span>Pilih fitur Scan QR atau kamera</span>
                    </div>
                    <div class="step">
                        <div class="step-num">3</div><span>Scan QR code di atas</span>
                    </div>
                    <div class="step">
                        <div class="step-num">4</div><span>Konfirmasi pembayaran sebesar <strong>Rp
                                {{ number_format($transaction->amount, 0, ',', '.') }}</strong></span>
                    </div>
                </div>
            @elseif($deeplink)
                <div class="qr-container">
                    <div class="qr-title">Lanjutkan di aplikasi {{ $transaction->payment_channel }}</div>
                    <div class="qr-amount">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</div>
                    <a href="{{ $deeplink }}" class="deeplink-btn">
                        <svg viewBox="0 0 24 24">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                            <polyline points="15 3 21 3 21 9" />
                            <line x1="10" y1="14" x2="21" y2="3" />
                        </svg>
                        Buka Aplikasi {{ $transaction->payment_channel }}
                    </a>
                    @if ($expiryTime)
                        <div class="qr-expiry">Berlaku hingga: <span class="timer" id="timer">--:--</span></div>
                    @endif
                </div>

                <div class="steps">
                    <div class="steps-title">Cara Bayar</div>
                    <div class="step">
                        <div class="step-num">1</div><span>Klik tombol "Buka Aplikasi" di atas</span>
                    </div>
                    <div class="step">
                        <div class="step-num">2</div><span>Konfirmasi pembayaran di aplikasi
                            {{ $transaction->payment_channel }}</span>
                    </div>
                    <div class="step">
                        <div class="step-num">3</div><span>Kembali ke sini dan klik "Cek Status"</span>
                    </div>
                </div>
            @endif

            {{-- Order summary --}}
            <div class="section-title">Ringkasan Pesanan</div>
            <div class="summary-card">
                <div class="summary-row">
                    <span>Kelas</span>
                    <span>{{ $booking->class_name ?? $schedule->class_name }}</span>
                </div>
                <div class="summary-row">
                    <span>Metode</span>
                    <span>{{ $transaction->payment_channel }}</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bottom-bar">
            <button class="btn-check" onclick="checkStatus()">Cek Status Pembayaran</button>
            <button class="btn-cancel" onclick="cancelPayment()">Batal</button>
        </div>
    </div>

    <script>
        @if ($expiryTime)
            const expiryTime = new Date('{{ $expiryTime }}');

            function updateTimer() {
                const now = new Date();
                const diff = expiryTime - now;
                if (diff <= 0) {
                    document.getElementById('timer').textContent = 'Kedaluwarsa';
                    return;
                }
                const mins = Math.floor(diff / 60000);
                const secs = Math.floor((diff % 60000) / 1000);
                document.getElementById('timer').textContent = mins + ':' + String(secs).padStart(2, '0');
            }
            updateTimer();
            setInterval(updateTimer, 1000);
        @endif

        function checkStatus() {
            const btn = event.target;
            btn.textContent = 'Mengecek...';
            btn.disabled = true;
            window.location.href = '{{ route('payment.check', $transaction->transaction_id) }}';
        }

        function cancelPayment() {
            if (confirm('Batalkan pembayaran ini?')) {
                window.location.href = '{{ route('payment.cancel', $booking->booking_id) }}';
            }
        }
    </script>
</body>

</html>
