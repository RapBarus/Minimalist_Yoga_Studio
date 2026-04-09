@extends('layouts.app')

@section('title', $coach->coach_name . ' | Minimalist Studio')

@push('styles')
    <style>
        .coach-profile-wrap {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 520px;
            margin: 0 auto;
        }

        /* Back */
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--clay);
            text-decoration: none;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
            width: fit-content;
        }

        .back-btn svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Hero */
        .coach-hero {
            background: var(--clay);
            border-radius: 18px;
            padding: 28px 20px 22px;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .coach-hero::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 130px;
            height: 130px;
            background: rgba(255, 255, 255, .07);
            border-radius: 50%;
        }

        .coach-hero::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: -20px;
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, .05);
            border-radius: 50%;
        }

        .coach-hero-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: #fff;
            border: 3px solid rgba(255, 255, 255, .45);
            position: relative;
            z-index: 1;
        }

        .coach-hero-name {
            font-size: 1.2rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
        }

        .coach-status-pill {
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            background: rgba(255, 255, 255, .18);
            padding: 3px 12px;
            border-radius: 20px;
            position: relative;
            z-index: 1;
        }

        /* Section heading */
        .section-heading {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--clay);
            margin-bottom: 10px;
        }

        /* Spec tags */
        .spec-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .spec-tag {
            padding: 6px 16px;
            border-radius: 20px;
            background: var(--clay);
            color: #fff;
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .04em;
        }

        /* Bio */
        .coach-bio {
            font-size: .85rem;
            line-height: 1.7;
            color: var(--text);
            margin: 0;
        }

        /* Rate box */
        .coach-rate-box {
            background: var(--blue-card, #C8DFF0);
            border-radius: 14px;
            padding: 16px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .rate-label {
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .rate-value {
            font-size: 1rem;
            font-weight: 700;
            color: var(--clay);
        }

        /* Upcoming schedules */
        .schedule-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .schedule-item {
            background: #fff;
            border: 1.5px solid var(--border, #e8e2db);
            border-radius: 12px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .schedule-item-left {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .schedule-item-class {
            font-size: .82rem;
            font-weight: 700;
            color: var(--clay);
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .schedule-item-date {
            font-size: .73rem;
            color: var(--text-muted);
        }

        .schedule-item-time {
            font-size: .73rem;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
        }

        .slot-pill {
            font-size: .68rem;
            font-weight: 600;
            background: rgba(160, 82, 45, .1);
            color: var(--clay);
            padding: 3px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }

        .slot-pill.full {
            background: rgba(0, 0, 0, .06);
            color: var(--text-muted);
        }

        .empty-schedule {
            font-size: .82rem;
            color: var(--text-muted);
            text-align: center;
            padding: 16px 0;
        }
    </style>
@endpush

@section('content')
    <div class="coach-profile-wrap">

        {{-- Back --}}
        <a href="{{ url()->previous() }}" class="back-btn">
            <svg viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Kembali
        </a>

        <div class="coach-hero">
            <div class="coach-hero-avatar">
                {{ strtoupper(substr($coach->coach_name, 0, 1)) }}
            </div>
            <div class="coach-hero-name">Coach {{ $coach->coach_name }}</div>

        </div>

        @if ($coach->specialization)
            <div>
                <div class="section-heading">Spesialis</div>
                <div class="spec-tags">
                    @foreach (explode(',', $coach->specialization) as $spec)
                        <span class="spec-tag">{{ trim($spec) }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Bio --}}
        <div>
            <div class="section-heading">Deskripsi Coach</div>
            <p class="coach-bio">{{ $coach->bio ?? 'Belum ada deskripsi.' }}</p>
        </div>

        {{-- Rate --}}
        <div class="coach-rate-box">
            <span class="rate-label">Rate per Sesi</span>
            <span class="rate-value">Rp {{ number_format($coach->rate_per_class ?? 0, 0, ',', '.') }}</span>
        </div>

        {{-- Upcoming schedules --}}
        <div>
            <div class="section-heading">Jadwal Mendatang</div>
            <div class="schedule-list">
                @forelse($coachSchedules as $s)
                    <div class="schedule-item">
                        <div class="schedule-item-left">
                            <div class="schedule-item-class">{{ $s->class_name }}</div>
                            <div class="schedule-item-date">
                                {{ \Carbon\Carbon::parse($s->schedule_date)->translatedFormat('l, d F Y') }}
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                            <div class="schedule-item-time">
                                {{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} –
                                {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}
                            </div>
                            <span class="slot-pill @if ($s->available_slots == 0) full @endif">
                                @if ($s->available_slots > 0)
                                    {{ $s->available_slots }} slot tersedia
                                @else
                                    Penuh
                                @endif
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="empty-schedule">Tidak ada jadwal mendatang.</p>
                @endforelse
            </div>
        </div>

    </div>
@endsection
