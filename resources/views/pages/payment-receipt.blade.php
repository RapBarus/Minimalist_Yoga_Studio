<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>Receipt | Minimalist Studio</title>
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
            --success: #27AE60;
            --success-pale: #eafaf1;
            --danger: #C0392B;
            --danger-pale: #fdecea;
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

        .topbar {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 18px 12px;
            background: var(--bg);
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
            padding: 40px 24px 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .status-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .status-icon.success {
            background: var(--success-pale);
            border: 2px solid #a9dfbf;
        }

        .status-icon.failed {
            background: var(--danger-pale);
            border: 2px solid #f5c6c2;
        }

        .status-icon svg {
            width: 30px;
            height: 30px;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .status-icon.success svg {
            stroke: var(--success);
        }

        .status-icon.failed svg {
            stroke: var(--danger);
        }

        .status-title {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text);
            text-align: center;
        }

        .receipt-card {
            background: var(--bg-white);
            border-radius: 16px;
            width: 100%;
            overflow: hidden;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
        }

        .receipt-top {
            padding: 20px;
            border-bottom: 1px solid var(--border);
        }

        .receipt-top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 4px 0;
            font-size: .85rem;
        }

        .receipt-top-label {
            color: var(--text-muted);
        }

        .receipt-top-amount {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 20px;
            padding: 3px 12px;
            font-size: .72rem;
            font-weight: 600;
        }

        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .status-badge.success {
            background: var(--success-pale);
            color: var(--success);
            border: 1px solid #a9dfbf;
        }

        .status-badge.success::before {
            background: var(--success);
        }

        .status-badge.failed {
            background: var(--danger-pale);
            color: var(--danger);
            border: 1px solid #f5c6c2;
        }

        .status-badge.failed::before {
            background: var(--danger);
        }

        .receipt-bottom {
            padding: 0 20px;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
            gap: 16px;
        }

        .receipt-row:last-child {
            border-bottom: none;
        }

        .receipt-label {
            font-size: .8rem;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .receipt-value {
            font-size: .82rem;
            font-weight: 600;
            color: var(--text);
            text-align: right;
        }

        /* Group members */
        .friends-list {
            display: flex;
            flex-direction: column;
            gap: 3px;
            text-align: right;
        }

        .friend-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: .75rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .friend-chip::before {
            content: '·';
            color: var(--clay);
        }

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
        <div class="topbar">
            <button class="back-btn" onclick="history.back()">
                <svg viewBox="0 0 24 24">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
            </button>
            <span class="topbar-title">Receipt</span>
        </div>

        @php
            $isSuccess = $transaction && in_array($transaction->status, ['settlement', 'paid']);
        @endphp

        <div class="content">

            @if ($isSuccess)
                <div class="status-icon success">
                    <svg viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                </div>
                <div class="status-title">Pembayaran Berhasil</div>
            @else
                <div class="status-icon failed">
                    <svg viewBox="0 0 24 24">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </div>
                <div class="status-title">Pembayaran Gagal</div>
            @endif

            <div class="receipt-card">
                <div class="receipt-top">
                    <div class="receipt-top-row">
                        <span class="receipt-top-label">Jumlah</span>
                        <span class="receipt-top-amount">IDR
                            {{ $transaction ? number_format($transaction->amount, 0, ',', '.') : '0' }}</span>
                    </div>
                    <div class="receipt-top-row" style="margin-top:8px;">
                        <span class="receipt-top-label">Status Pembayaran</span>
                        <span class="status-badge {{ $isSuccess ? 'success' : 'failed' }}">
                            {{ $isSuccess ? 'Berhasil' : 'Gagal' }}
                        </span>
                    </div>
                </div>

                <div class="receipt-bottom">
                    <div class="receipt-row">
                        <span class="receipt-label">Nama Kelas</span>
                        <span class="receipt-value">{{ strtoupper($schedule->class_name) }}</span>
                    </div>

                    {{-- Peserta --}}
                    <div class="receipt-row">
                        <span class="receipt-label">Peserta</span>
                        <div class="friends-list">
                            <span class="receipt-value">{{ $user->name }}</span>
                            @if (!empty($groupMembers))
                                @foreach ($groupMembers as $friend)
                                    <span class="friend-chip">{{ $friend }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    @if (!empty($groupMembers))
                        <div class="receipt-row">
                            <span class="receipt-label">Total Peserta</span>
                            <span class="receipt-value">{{ 1 + count($groupMembers) }} orang</span>
                        </div>
                    @endif

                    <div class="receipt-row">
                        <span class="receipt-label">Pembayaran</span>
                        <span class="receipt-value">
                            {{ $transaction ? $transaction->payment_channel ?? 'Membership Quota' : '-' }}
                        </span>
                    </div>
                    <div class="receipt-row">
                        <span class="receipt-label">Waktu Pembayaran</span>
                        <span class="receipt-value">
                            {{ $transaction ? \Carbon\Carbon::parse($transaction->updated_at)->format('d M Y, H:i') : now()->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-bar">
            @if ($isSuccess)
                <a href="{{ route('activity') }}" class="btn-selesai">Selesai</a>
            @else
                <a href="{{ route('payment.show', $schedule->schedule_id) }}" class="btn-selesai">
                    Kembali Ke Pembayaran
                </a>
            @endif
        </div>
    </div>
</body>

</html>
