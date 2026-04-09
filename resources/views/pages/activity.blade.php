@extends('layouts.app')

@section('title', 'Aktivitas | Minimalist Studio')

@push('styles')
    <style>
        .content {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding-bottom: 90px;
        }

        .section-label {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--clay);
            margin-bottom: 10px;
            margin-top: 8px;
        }

        /* ── Activity Card ── */
        .card-activity {
            background: var(--clay);
            border-radius: 16px;
            padding: 16px 18px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(160, 82, 45, .25);
            margin-bottom: 12px;
        }

        .card-activity::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .card-activity-title {
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .coach-badge-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
            text-decoration: none;
            color: inherit;
            border-radius: 20px;
            padding: 3px 10px 3px 3px;
            transition: background .18s;
            background: rgba(255, 255, 255, .15);
        }

        .coach-badge-link:hover {
            background: rgba(255, 255, 255, .25);
        }

        .coach-avatar {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .65rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .card-activity-meta {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 10px;
        }

        .card-activity-meta-row {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: .78rem;
            opacity: .9;
        }

        .card-activity-meta-row svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .card-activity-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .card-activity-price {
            font-weight: 700;
            font-size: .95rem;
        }

        .price-detail {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .price-original {
            font-size: .72rem;
            opacity: .7;
            text-decoration: line-through;
        }

        .pertemuan-badge {
            font-size: .72rem;
            background: rgba(255, 255, 255, .15);
            padding: 2px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .btn-receipt {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            width: 100%;
            padding: .6rem 1rem;
            background: rgba(255, 255, 255, .15);
            color: #fff;
            border: 1.5px solid rgba(255, 255, 255, .35);
            border-radius: 10px;
            text-decoration: none;
            font-family: 'Raleway', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            transition: background .18s;
            cursor: pointer;
        }

        .btn-receipt:hover {
            background: rgba(255, 255, 255, .28);
        }

        .btn-receipt svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        /* History cards — blue variant */
        .card-activity.history {
            background: var(--blue-card, #C8DFF0);
            color: #3a3a3a;
            box-shadow: 0 4px 16px rgba(100, 150, 200, .18);
        }

        .card-activity.history .coach-badge-link {
            background: rgba(0, 0, 0, .07);
            color: #3a3a3a;
        }

        .card-activity.history .coach-badge-link:hover {
            background: rgba(0, 0, 0, .13);
        }

        .card-activity.history .coach-avatar {
            background: rgba(160, 82, 45, .25);
            color: var(--clay);
        }

        .card-activity.history .btn-receipt {
            background: rgba(160, 82, 45, .12);
            border-color: rgba(160, 82, 45, .25);
            color: var(--clay);
        }

        .card-activity.history .btn-receipt:hover {
            background: rgba(160, 82, 45, .22);
        }

        .card-activity.history .card-activity-meta-row {
            opacity: .75;
            color: #3a3a3a;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 28px 0 12px;
            color: var(--text-muted);
            font-size: .82rem;
        }

        .empty-state svg {
            width: 36px;
            height: 36px;
            stroke: var(--clay);
            opacity: .3;
            fill: none;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            margin-bottom: 8px;
            display: block;
            margin: 0 auto 8px;
        }
    </style>
@endpush

@section('content')
    <div class="content">

        {{-- ── Active Bookings ── --}}
        <div class="section-label">Aktivitas Anda</div>

        @forelse ($activeBookings as $booking)
            @php
                $initial = strtoupper(substr($booking->coach_name ?? 'C', 0, 1));
            @endphp
            <div class="card-activity">
                <div class="card-activity-title">{{ $booking->class_name }}</div>

                {{-- Coach --}}
                @if (!empty($booking->coach_name))
                    <a href="{{ route('coach.profile', $booking->coach_id) }}" class="coach-badge-link">
                        <div class="coach-avatar">{{ $initial }}</div>
                        <span style="font-size:.75rem;">{{ $booking->coach_name }}</span>
                    </a>
                @endif

                {{-- Date & Time --}}
                <div class="card-activity-meta">
                    <div class="card-activity-meta-row">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        {{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="card-activity-meta-row">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} –
                        {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB
                    </div>
                </div>

                {{-- Price & Pertemuan --}}
                <div class="card-activity-footer">
                    <div class="price-detail">
                        @if (!empty($booking->original_price) && $booking->original_price != $booking->amount)
                            <span class="price-original">Rp
                                {{ number_format($booking->original_price, 0, ',', '.') }}</span>
                        @endif
                        <span class="card-activity-price">Rp {{ number_format($booking->amount, 0, ',', '.') }}</span>
                    </div>
                    @if (!empty($booking->pertemuan))
                        <span class="pertemuan-badge">Sisa pertemuan : {{ $booking->pertemuan }}</span>
                    @endif
                </div>

                <a href="#" class="btn-receipt">
                    <svg viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                    Receipt
                </a>
            </div>
        @empty
            <div class="empty-state">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                Tidak ada aktivitas aktif saat ini.
            </div>
        @endforelse

        {{-- ── History ── --}}
        <div class="section-label" style="margin-top:8px;">Riwayat Aktivitas</div>

        @forelse ($historyBookings as $booking)
            @php
                $initial = strtoupper(substr($booking->coach_name ?? 'C', 0, 1));
            @endphp
            <div class="card-activity history">
                <div class="card-activity-title">{{ $booking->class_name }}</div>

                @if (!empty($booking->coach_name))
                    <a href="{{ route('coach.profile', $booking->coach_id) }}" class="coach-badge-link">
                        <div class="coach-avatar">{{ $initial }}</div>
                        <span style="font-size:.75rem;">{{ $booking->coach_name }}</span>
                    </a>
                @endif

                <div class="card-activity-meta">
                    <div class="card-activity-meta-row">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        {{ \Carbon\Carbon::parse($booking->schedule_date)->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="card-activity-meta-row">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} –
                        {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB
                    </div>
                </div>

                <div class="card-activity-footer">
                    <span class="card-activity-price">Rp {{ number_format($booking->amount, 0, ',', '.') }}</span>
                </div>

                <a href="#" class="btn-receipt">
                    <svg viewBox="0 0 24 24">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                    Receipt
                </a>
            </div>
        @empty
            <div class="empty-state">
                Belum ada riwayat aktivitas.
            </div>
        @endforelse

    </div>
@endsection
