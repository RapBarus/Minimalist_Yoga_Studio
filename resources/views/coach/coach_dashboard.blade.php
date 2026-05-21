@extends('layouts.coach')

@section('title', 'Dashboard Coach | Minimalist Studio')

@push('styles')
    <style>
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
            margin-bottom: 8px;
        }

        .sc-coach-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255, 255, 255, .15);
            border-radius: 20px;
            padding: 3px 10px 3px 3px;
            font-size: .73rem;
            margin-bottom: 8px;
        }

        .sc-coach-avatar {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .6rem;
            font-weight: 700;
        }

        .sc-meta {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 10px;
        }

        .sc-meta-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .75rem;
            opacity: .9;
        }

        .sc-meta-row svg {
            width: 12px;
            height: 12px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .sc-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .sc-price {
            font-weight: 700;
            font-size: .9rem;
        }

        .sc-quota {
            font-size: .72rem;
            opacity: .85;
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
    </style>
@endpush

@section('content')
    <div class="coach-page-title">Jadwal Kelas Anda</div>

    <div class="coach-content">
        <div style="display:flex;gap:8px;">
            <a href="{{ route('coach.dashboard', ['filter' => 'all']) }}"
                style="padding:7px 18px;border-radius:20px;border:1.5px solid var(--clay);
                  background:{{ $filter === 'all' ? 'var(--clay)' : 'transparent' }};
                  color:{{ $filter === 'all' ? '#fff' : 'var(--clay)' }};
                  font-family:'Raleway',sans-serif;font-size:.75rem;font-weight:500;
                  text-decoration:none;white-space:nowrap;">
                Semua
            </a>
            <a href="{{ route('coach.dashboard', ['filter' => 'week']) }}"
                style="padding:7px 18px;border-radius:20px;border:1.5px solid var(--clay);
                  background:{{ $filter === 'week' ? 'var(--clay)' : 'transparent' }};
                  color:{{ $filter === 'week' ? '#fff' : 'var(--clay)' }};
                  font-family:'Raleway',sans-serif;font-size:.75rem;font-weight:500;
                  text-decoration:none;white-space:nowrap;">
                Minggu Ini
            </a>
            <a href="{{ route('coach.dashboard', ['filter' => 'month']) }}"
                style="padding:7px 18px;border-radius:20px;border:1.5px solid var(--clay);
                  background:{{ $filter === 'month' ? 'var(--clay)' : 'transparent' }};
                  color:{{ $filter === 'month' ? '#fff' : 'var(--clay)' }};
                  font-family:'Raleway',sans-serif;font-size:.75rem;font-weight:500;
                  text-decoration:none;white-space:nowrap;">
                Bulan Ini
            </a>
        </div>
        @forelse($schedules as $schedule)
            @php $initial = strtoupper(substr(Session::get('user_name', 'C'), 0, 1)); @endphp
            <div class="schedule-card">
                <div class="sc-title">{{ $schedule->class_name }}</div>

                <div class="sc-coach-badge">
                    <div class="sc-coach-avatar">{{ $initial }}</div>
                    {{ Session::get('user_name') }}
                </div>

                <div class="sc-meta">
                    <div class="sc-meta-row">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="sc-meta-row">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} –
                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                    </div>
                </div>

                <div class="sc-footer">
                    <span class="sc-price">Rp {{ number_format($schedule->rate_per_class ?? 0, 0, ',', '.') }}</span>
                    <span class="sc-quota">Kuota tersedia : {{ $schedule->available_slots }}</span>
                </div>

                <a href="{{ route('coach.schedule.detail', $schedule->schedule_id) }}" class="btn-cek-jadwal">Cek
                    Jadwal</a>
            </div>
        @empty
            <div class="empty-state">
                Tidak ada jadwal kelas hari ini.
            </div>
        @endforelse
    </div>
@endsection
