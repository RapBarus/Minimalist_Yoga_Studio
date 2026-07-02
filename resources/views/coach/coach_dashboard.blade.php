@extends('layouts.coach')

@section('title', 'Dashboard Coach | Minimalist Studio')

@push('styles')
    <style>
        html {
            overflow-y: scroll;
        }

        .schedule-card {
            background: var(--clay);
            border-radius: 16px;
            padding: 16px 18px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .schedule-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .sc-title {
            font-weight: 700;
            font-size: .95rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        /* Status badge */
        .sc-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .73rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .sc-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .sc-status.ongoing {
            color: #F5C842;
        }

        .sc-status.ongoing .sc-status-dot {
            background: #F5C842;
        }

        .sc-status.upcoming {
            color: rgba(255, 255, 255, .85);
        }

        .sc-status.upcoming .sc-status-dot {
            background: rgba(255, 255, 255, .7);
        }

        .sc-status.completed {
            color: #4CD97B;
        }

        .sc-status.completed .sc-status-dot {
            background: #4CD97B;
        }

        /* Meta row: date + price on one line, time + quota on next */
        .sc-meta-grid {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 4px 8px;
            margin-bottom: 10px;
            font-size: .75rem;
            opacity: .9;
        }

        .sc-meta-grid .sc-meta-left {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .sc-meta-grid .sc-meta-right {
            text-align: right;
            font-weight: 700;
            font-size: .85rem;
            opacity: 1;
        }

        .sc-meta-grid svg {
            width: 12px;
            height: 12px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .sc-quota {
            font-size: .72rem;
            opacity: .85;
            text-align: right;
        }

        .btn-cek-jadwal {
            display: block;
            width: 100%;
            padding: .6rem;
            background: rgba(255, 255, 255, .2);
            color: #fff;
            border: 1.5px solid rgba(255, 255, 255, .35);
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-cek-jadwal:hover {
            background: rgba(255, 255, 255, .32);
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: var(--text-muted);
            font-size: .85rem;
        }

        .filter-btn {
            padding: 7px 18px;
            border-radius: 20px;
            border: 1.5px solid var(--clay);
            background: transparent;
            color: var(--clay);
            font-family: 'Raleway', sans-serif;
            font-size: .75rem;
            font-weight: 500;
            cursor: pointer;
            white-space: nowrap;
            transition: background .15s, color .15s;
        }

        .filter-btn.filter-active {
            background: var(--clay);
            color: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="coach-page-title" id="page-title">Jadwal Kelas Anda</div>

    <div class="coach-content">

        {{-- Filter buttons --}}
        <div style="display:flex;gap:8px;">
            <button type="button" class="filter-btn" data-filter="all" onclick="applyFilter('all')">Semua</button>
            <button type="button" class="filter-btn" data-filter="today" onclick="applyFilter('today')">Hari Ini</button>
            <button type="button" class="filter-btn" data-filter="week" onclick="applyFilter('week')">Minggu Ini</button>
        </div>

        {{-- Schedule cards --}}
        @foreach ($schedules as $schedule)
            @php
                $initial = strtoupper(substr(Session::get('user_name', 'C'), 0, 1));
                $now = now();
                $date = $schedule->schedule_date;
                $start = \Carbon\Carbon::parse($date . ' ' . $schedule->start_time);
                $end = \Carbon\Carbon::parse($date . ' ' . $schedule->end_time);

                if ($schedule->status === 'completed') {
                    $statusKey = 'completed';
                    $statusLabel = 'Sudah selesai';
                } elseif ($now->between($start, $end)) {
                    $statusKey = 'ongoing';
                    $statusLabel = 'Sedang berlangsung';
                } else {
                    $statusKey = 'upcoming';
                    $statusLabel = 'Belum dimulai';
                }
            @endphp

            <div class="schedule-card" data-date="{{ $date }}">
                <div class="sc-title">{{ $schedule->class_name }}</div>

                {{-- Status badge --}}
                <div class="sc-status {{ $statusKey }}">
                    <span class="sc-status-dot"></span>
                    {{ $statusLabel }}
                </div>

                {{-- Date + Price row --}}
                <div class="sc-meta-grid">
                    <div class="sc-meta-left">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="sc-meta-right">
                        Rp {{ number_format($schedule->rate_per_class ?? 0, 0, ',', '.') }}
                    </div>

                    {{-- Time + Quota row --}}
                    <div class="sc-meta-left">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} –
                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                    </div>
                    <div class="sc-quota">Kuota tersedia : {{ $schedule->available_slots }}</div>
                </div>

                <a href="{{ route('coach.schedule.detail', $schedule->schedule_id) }}" class="btn-cek-jadwal">Cek Jadwal</a>
            </div>
        @endforeach

        <div id="empty-state" class="empty-state" style="display:none;">
            Tidak ada jadwal kelas pada periode ini.
        </div>

        @if ($schedules->isEmpty())
            <div class="empty-state">Tidak ada jadwal kelas.</div>
        @endif

    </div>
@endsection

@push('scripts')
    <script>
        const TODAY = '{{ now()->toDateString() }}';
        const WEEK_END = '{{ now()->addDays(7)->toDateString() }}';

        const TITLES = {
            all: 'Jadwal Kelas Anda',
            today: 'Jadwal Kelas Anda Hari Ini',
            week: 'Jadwal Kelas Anda Minggu Ini',
        };

        function applyFilter(filter) {
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.toggle('filter-active', btn.dataset.filter === filter);
            });

            document.getElementById('page-title').textContent = TITLES[filter] || TITLES.all;

            const cards = document.querySelectorAll('.schedule-card');
            let anyVisible = false;

            cards.forEach(card => {
                const date = card.dataset.date;
                let show = false;

                if (filter === 'all') show = true;
                else if (filter === 'today') show = date === TODAY;
                else if (filter === 'week') show = date >= TODAY && date <= WEEK_END;

                card.style.display = show ? '' : 'none';
                if (show) anyVisible = true;
            });

            const emptyState = document.getElementById('empty-state');
            if (emptyState) emptyState.style.display = anyVisible ? 'none' : 'block';

            history.replaceState(null, '', '?filter=' + filter);
        }

        applyFilter('{{ $filter }}');
    </script>
@endpush
