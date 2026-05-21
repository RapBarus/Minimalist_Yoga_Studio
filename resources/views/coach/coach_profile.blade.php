@extends('layouts.coach')

@section('title', 'Profil | Minimalist Studio')

@push('styles')
    <style>
        .profile-card {
            background: var(--clay);
            border-radius: 14px;
            padding: 18px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 14px;
            position: relative;
            overflow: hidden;
        }

        .profile-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .profile-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .profile-name {
            font-weight: 700;
            font-size: .95rem;
            letter-spacing: .03em;
        }

        .profile-spec {
            font-size: .75rem;
            opacity: .8;
            margin-top: 2px;
        }

        .section-title {
            font-weight: 700;
            font-size: 1rem;
            color: var(--clay);
            letter-spacing: .04em;
        }

        .date-range-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .date-range-wrap input[type="date"] {
            flex: 1;
            padding: .65rem .9rem;
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            color: var(--text);
            outline: none;
            transition: border-color .2s;
        }

        .date-range-wrap input[type="date"]:focus {
            border-color: var(--clay);
        }

        .date-range-sep {
            font-size: .8rem;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .btn-filter {
            padding: .65rem 1rem;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .75rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .18s;
            flex-shrink: 0;
        }

        .btn-filter:hover {
            background: var(--clay-dark);
        }

        .stats-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .stat-box {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 20px 16px;
            text-align: center;
        }

        .stat-box-label {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 10px;
        }

        .stat-box-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.2rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1;
        }

        .pendapatan-box {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 20px;
        }

        .pendapatan-label {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 10px;
        }

        .pendapatan-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--clay);
            line-height: 1;
        }

        .btn-logout-full {
            width: 100%;
            padding: .85rem;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background .18s;
        }

        .btn-logout-full:hover {
            background: #b91c1c;
        }

        .graph-wrap {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 16px;
            padding: 20px;
        }

        .graph-title {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 16px;
        }

        .graph-canvas-wrap {
            position: relative;
            height: 200px;
        }
    </style>
@endpush

@section('content')

    <div class="coach-page-title">Profil Anda</div>

    <div class="coach-content">

        {{-- Profile card --}}
        <div class="profile-card">
            <div class="profile-avatar">
                {{ strtoupper(substr($coach->name, 0, 1)) }}
            </div>
            <div>
                <div class="profile-name">Coach {{ $coach->name }}</div>
                <div class="profile-spec">{{ $coach->class_name }}</div>
            </div>
        </div>

        {{-- Pendapatan section --}}
        <div class="section-title">Pendapatan Anda</div>

        {{-- Date filter --}}
        <form method="GET" action="{{ route('coach.profile') }}">
            <div class="date-range-wrap">
                <input type="date" name="from" value="{{ $from }}">
                <span class="date-range-sep">–</span>
                <input type="date" name="to" value="{{ $to }}">
                <button type="submit" class="btn-filter">Filter</button>
            </div>
        </form>

        {{-- Stats --}}
        <div class="stats-2col">
            <div class="stat-box">
                <div class="stat-box-label">Total Kelas</div>
                <div class="stat-box-value">{{ $totalKelas }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-box-label">Total Peserta</div>
                <div class="stat-box-value">{{ $totalPeserta }}</div>
            </div>
        </div>

        {{-- Total Pendapatan --}}
        <div class="pendapatan-box">
            <div class="pendapatan-label">Total Pendapatan</div>
            <div class="pendapatan-value">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
        </div>
        {{-- Graph --}}
        <div class="graph-wrap">
            <div class="graph-title">Pendapatan per Kelas</div>
            <div class="graph-canvas-wrap">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout-full">Log Out</button>
        </form>

    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json($chartValues),
                    backgroundColor: 'rgba(160, 82, 45, 0.75)',
                    borderColor: 'rgba(160, 82, 45, 1)',
                    borderWidth: 1.5,
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: val => 'Rp ' + (val / 1000) + 'k',
                            font: {
                                family: 'Raleway',
                                size: 10
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,.06)'
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                family: 'Raleway',
                                size: 10
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endpush
