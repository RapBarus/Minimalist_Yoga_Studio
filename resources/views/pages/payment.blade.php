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
            --bg: #F2EFEB;
            --bg-white: #FFFFFF;
            --text: #3A2E28;
            --text-muted: #9A8C82;
            --border: #E0D8D0;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        .page {
            max-width: 680px;
            margin: 0 auto;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            animation: fadeUp .45s ease both;
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

        /* ── Scrollable content ── */
        .content {
            flex: 1;
            padding: 20px 18px 160px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .section-title {
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 4px;
            margin-top: 8px;
        }

        /* ── Payment method card ── */
        .method-card {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: border-color .18s, box-shadow .18s;
        }

        .method-card:hover {
            border-color: var(--clay);
            box-shadow: 0 2px 12px rgba(160, 82, 45, .10);
        }

        .method-card.selected {
            border-color: var(--clay);
            box-shadow: 0 2px 16px rgba(160, 82, 45, .15);
        }

        .method-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .method-logo {
            width: 72px;
            height: 32px;
            object-fit: contain;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Inline SVG logos */
        .logo-qris {
            font-weight: 900;
            font-size: 1.1rem;
            letter-spacing: .06em;
            color: #1a1a1a;
            font-family: 'Raleway', sans-serif;
        }

        .logo-gopay {
            color: #00AED6;
            font-weight: 700;
            font-size: .95rem;
        }

        .logo-dana {
            color: #108BE3;
            font-weight: 700;
            font-size: .95rem;
        }

        .logo-ovo {
            color: #4C3494;
            font-weight: 900;
            font-size: 1.1rem;
            letter-spacing: -.02em;
        }

        .logo-shopeepay {
            color: #EE4D2D;
            font-weight: 700;
            font-size: .85rem;
        }

        /* Radio button */
        .radio {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color .18s;
            flex-shrink: 0;
        }

        .method-card.selected .radio {
            border-color: var(--clay);
        }

        .radio-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--clay);
            opacity: 0;
            transform: scale(0);
            transition: opacity .18s, transform .18s;
        }

        .method-card.selected .radio-dot {
            opacity: 1;
            transform: scale(1);
        }

        /* ── Order summary ── */
        .summary-card {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 16px 18px;
            margin-top: 8px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: .82rem;
            color: var(--text-muted);
            padding: 5px 0;
        }

        .summary-row.total {
            font-weight: 700;
            font-size: .9rem;
            color: var(--text);
            border-top: 1px solid var(--border);
            margin-top: 6px;
            padding-top: 10px;
        }

        .summary-row span:last-child {
            color: var(--text);
        }

        .summary-row.total span:last-child {
            color: var(--clay);
        }

        /* ── Fixed bottom pay button ── */
        .pay-bar {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 680px;
            padding: 16px 18px 24px;
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
            transition: background .18s, transform .18s, box-shadow .18s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .pay-btn:hover {
            background: var(--clay-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(160, 82, 45, .36);
        }

        .pay-btn:active {
            transform: translateY(0);
        }

        .pay-divider {
            width: 1px;
            height: 18px;
            background: rgba(255, 255, 255, .35);
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
            <span class="topbar-title">Metode Pembayaran</span>
        </div>

        <div class="content">

            <div class="section-title">Pilih Metode</div>

            {{-- QRIS --}}
            <div class="method-card selected" onclick="selectMethod(this, 'QRIS')">
                <div class="method-left">
                    <div class="method-logo">
                        <span class="logo-qris">&#9646;QRIS</span>
                    </div>
                </div>
                <div class="radio">
                    <div class="radio-dot"></div>
                </div>
            </div>

            {{-- GoPay --}}
            <div class="method-card" onclick="selectMethod(this, 'GoPay')">
                <div class="method-left">
                    <div class="method-logo">
                        <span class="logo-gopay">● gopay</span>
                    </div>
                </div>
                <div class="radio">
                    <div class="radio-dot"></div>
                </div>
            </div>

            {{-- DANA --}}
            <div class="method-card" onclick="selectMethod(this, 'DANA')">
                <div class="method-left">
                    <div class="method-logo">
                        <span class="logo-dana">⊙ DANA</span>
                    </div>
                </div>
                <div class="radio">
                    <div class="radio-dot"></div>
                </div>
            </div>

            {{-- OVO --}}
            <div class="method-card" onclick="selectMethod(this, 'OVO')">
                <div class="method-left">
                    <div class="method-logo">
                        <span class="logo-ovo">OVO</span>
                    </div>
                </div>
                <div class="radio">
                    <div class="radio-dot"></div>
                </div>
            </div>

            {{-- ShopeePay --}}
            <div class="method-card" onclick="selectMethod(this, 'ShopeePay')">
                <div class="method-left">
                    <div class="method-logo">
                        <span class="logo-shopeepay">🛍 ShopeePay</span>
                    </div>
                </div>
                <div class="radio">
                    <div class="radio-dot"></div>
                </div>
            </div>

            {{-- Order summary --}}
            <div class="section-title" style="margin-top: 16px;">Rincian Pesanan</div>

            <div class="summary-card">
                <div class="summary-row">
                    <span>{{ $schedule->class_name ?? 'Kelas Yoga' }}</span>
                    <span>Rp {{ number_format($schedule->price ?? 50000, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Biaya Admin</span>
                    <span>Rp 5.000</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>Rp {{ number_format(($schedule->price ?? 50000) + 5000, 0, ',', '.') }}</span>
                </div>
            </div>

        </div>
    </div>

    {{-- Fixed pay button --}}
    <div class="pay-bar">
        <button class="pay-btn" id="pay-btn" onclick="handlePayment()">
            <span>Bayar</span>
            <div class="pay-divider"></div>
            <span id="pay-amount">Rp {{ number_format(($schedule->price ?? 50000) + 5000, 0, ',', '.') }}</span>
        </button>
    </div>

    <script>
        function selectMethod(el, name) {
            document.querySelectorAll('.method-card').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
            window.selectedMethod = name;
        }

        function handlePayment() {
            const method = window.selectedMethod || 'QRIS';
            const btn = document.getElementById('pay-btn');
            btn.textContent = 'Memproses...';
            btn.disabled = true;
            btn.style.opacity = '.7';

            // Submit to backend
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('payment.process') }}';

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = 'payment_method';
            methodInput.value = method;

            const scheduleInput = document.createElement('input');
            scheduleInput.type = 'hidden';
            scheduleInput.name = 'schedule_id';
            scheduleInput.value = '{{ $schedule->schedule_id ?? '' }}';

            form.appendChild(csrf);
            form.appendChild(methodInput);
            form.appendChild(scheduleInput);
            document.body.appendChild(form);
            form.submit();
        }

        window.selectedMethod = 'QRIS';
    </script>

</body>

</html>
