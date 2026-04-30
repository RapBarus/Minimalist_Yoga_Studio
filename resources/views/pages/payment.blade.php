<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>Pembayaran | Minimalist Studio</title>

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
            align-items: flex-start;
        }

        .page {
            max-width: 560px;
            width: 100%;
            min-height: 100vh;
            background: var(--bg);
            display: flex;
            flex-direction: column;
            animation: fadeUp .45s ease both;
            box-shadow: 0 0 40px rgba(0, 0, 0, .08);
        }

        /* ── Top bar ── */
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
            transition: opacity .18s;
        }

        .back-btn:hover {
            opacity: .6;
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
            color: var(--text);
        }

        /* ── Content ── */
        .content {
            flex: 1;
            padding: 24px 18px 160px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .section-title {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        /* ── Class info card ── */
        .class-card {
            background: var(--clay);
            border-radius: 16px;
            padding: 18px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .class-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .class-name {
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .class-meta {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .class-meta-row {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: .78rem;
            opacity: .9;
        }

        .class-meta-row svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        /* ── Summary card ── */
        .summary-card {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 16px 18px;
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

        /* ── Xendit notice ── */
        .xendit-notice {
            background: var(--clay-pale);
            border-radius: 12px;
            padding: 14px 16px;
            font-size: .78rem;
            color: var(--text-muted);
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.5;
        }

        .xendit-notice svg {
            width: 16px;
            height: 16px;
            stroke: var(--clay);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* ── Pay button ── */
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
            opacity: .7;
            cursor: not-allowed;
            transform: none;
        }

        .pay-divider {
            width: 1px;
            height: 18px;
            background: rgba(255, 255, 255, .35);
        }

        /* ── Alert ── */
        .alert-error {
            background: #fdecea;
            color: #C0392B;
            border: 1px solid #f5c6c2;
            border-radius: 10px;
            padding: .7rem .9rem;
            font-size: .78rem;
            text-align: center;
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

        {{-- Top bar --}}
        <div class="topbar">
            <button class="back-btn" onclick="history.back()">
                <svg viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
            </button>
            <span class="topbar-title">Konfirmasi Pembayaran</span>
        </div>

        <div class="content">

            @if ($errors->any())
                <div class="alert-error">{{ $errors->first() }}</div>
            @endif

            {{-- Class info --}}
            <div class="section-title">Detail Kelas</div>

            <div class="class-card">
                <div class="class-name">{{ $schedule->class_name }}</div>
                <div class="class-meta">
                    <div class="class-meta-row">
                        <svg viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                        {{ $schedule->coach_name }}
                    </div>
                    <div class="class-meta-row">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="class-meta-row">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} –
                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                    </div>
                </div>
            </div>

            {{-- Order summary --}}
            <div class="section-title">Rincian Pembayaran</div>

            <div class="summary-card">
                <div class="summary-row">
                    <span>{{ $schedule->class_name }}</span>
                    <span>Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Xendit notice --}}
            <div class="xendit-notice">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                Anda akan diarahkan ke halaman pembayaran Xendit. Pilih metode pembayaran (QRIS, Virtual Account, GoPay,
                OVO, DANA, ShopeePay) di sana.
            </div>

        </div>

        {{-- Pay button --}}
        <div class="pay-bar">
            <button class="pay-btn" id="pay-btn" onclick="handlePayment()">
                <span>Lanjut Pembayaran</span>
                <div class="pay-divider"></div>
                <span>Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
            </button>
        </div>

    </div>

    <script>
        function handlePayment() {
            const btn = document.getElementById('pay-btn');
            btn.disabled = true;
            btn.innerHTML = '<span>Memproses...</span>';

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('payment.process') }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';

            const scheduleInput = document.createElement('input');
            scheduleInput.type = 'hidden';
            scheduleInput.name = 'schedule_id';
            scheduleInput.value = '{{ $schedule->schedule_id }}';

            form.appendChild(csrf);
            form.appendChild(scheduleInput);
            document.body.appendChild(form);
            form.submit();
        }
    </script>

</body>

</html>
