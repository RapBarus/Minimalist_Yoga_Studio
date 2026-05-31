@extends('layouts.admin')

@section('title', 'Keuangan | Minimalist Studio')
@section('page-title', 'Keuangan')
@section('page-sub', 'Laporan keuangan studio')

@push('styles')
    <style>
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

        /* Stat cards */
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

        /* Graph */
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

        /* PDF button */
        .btn-cetak-pdf {
            width: 100%;
            padding: .9rem;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background .18s, transform .15s;
            box-shadow: 0 4px 16px rgba(160, 82, 45, .28);
        }

        .btn-cetak-pdf:hover {
            background: var(--clay-dark);
            transform: translateY(-1px);
        }

        .btn-keluar {
            width: 100%;
            padding: .85rem;
            background: transparent;
            color: var(--danger);
            border: 1.5px solid var(--danger);
            border-radius: 12px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background .18s, color .18s;
        }

        .btn-keluar:hover {
            background: var(--danger);
            color: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="content">

        {{-- Date range filter --}}
        <form method="GET" action="{{ route('admin.keuangan') }}">
            <div class="date-range-wrap">
                <input type="date" name="from" value="{{ $from }}">
                <span class="date-range-sep">–</span>
                <input type="date" name="to" value="{{ $to }}">
                <button type="submit" class="btn-filter">Filter</button>
            </div>
        </form>

        {{-- Stats row --}}
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
                Rp {{ number_format($totalPendapatan, 2, '.', '.') }}
            </div>
        </div>

        {{-- Graph --}}
        <div class="graph-wrap">
            <div class="graph-title">Pendapatan per Kelas</div>
            <div class="graph-canvas-wrap">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Cetak PDF --}}
        <button class="btn-cetak-pdf" onclick="printReport()">
            CETAK PDF
        </button>
        {{-- Keluar --}}
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="btn-keluar">
                <svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                    <polyline points="16 17 21 12 16 7" />
                    <line x1="21" y1="12" x2="9" y2="12" />
                </svg>
                Keluar
            </button>
        </form>

    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        // Chart data from controller
        const chartLabels = @json($chartLabels);
        const chartData = @json($chartData);

        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: chartData,
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

        function printReport() {
            const from = document.querySelector('input[name="from"]').value;
            const to = document.querySelector('input[name="to"]').value;
            const title = 'Laporan Keuangan Minimalist Studio';
            const total = document.querySelector('.pendapatan-value').textContent.trim();
            const kelas = '{{ $totalKelas }}';
            const peserta = '{{ $totalPeserta }}';

            const win = window.open('', '_blank');
            win.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>${title}</title>
                <style>
                    body { font-family: 'Raleway', sans-serif; padding: 40px; color: #3A2E28; }
                    h1 { font-size: 1.4rem; margin-bottom: 4px; }
                    .period { color: #9A8C82; font-size: .85rem; margin-bottom: 24px; }
                    .stats { display: flex; gap: 20px; margin-bottom: 24px; }
                    .stat { background: #f5f5f5; border-radius: 10px; padding: 16px 24px; text-align: center; }
                    .stat-label { font-size: .7rem; text-transform: uppercase; letter-spacing: .08em; color: #9A8C82; margin-bottom: 4px; }
                    .stat-value { font-size: 1.5rem; font-weight: 700; color: #3A2E28; }
                    .pendapatan { background: #A0522D; color: #fff; border-radius: 12px; padding: 20px 24px; margin-bottom: 24px; }
                    .pendapatan-label { font-size: .7rem; text-transform: uppercase; letter-spacing: .08em; opacity: .8; margin-bottom: 6px; }
                    .pendapatan-value { font-size: 1.8rem; font-weight: 700; }
                    table { width: 100%; border-collapse: collapse; }
                    th { background: #A0522D; color: #fff; padding: 10px 14px; text-align: left; font-size: .75rem; }
                    td { padding: 10px 14px; border-bottom: 1px solid #eee; font-size: .82rem; }
                </style>
            </head>
            <body>
                <h1>${title}</h1>
                <div class="period">Periode: ${from} – ${to}</div>
                <div class="stats">
                    <div class="stat"><div class="stat-label">Total Kelas</div><div class="stat-value">${kelas}</div></div>
                    <div class="stat"><div class="stat-label">Total Peserta</div><div class="stat-value">${peserta}</div></div>
                </div>
                <div class="pendapatan">
                    <div class="pendapatan-label">Total Pendapatan</div>
                    <div class="pendapatan-value">${total}</div>
                </div>
                <table>
                    <thead><tr><th>Kelas</th><th>Peserta</th><th>Pendapatan</th></tr></thead>
                    <tbody>
                        ${@json($tableRows).map(r => `<tr><td>${r.class_name}</td><td>${r.peserta}</td><td>Rp ${Number(r.pendapatan).toLocaleString('id-ID')}</td></tr>`).join('')}
                    </tbody>
                </table>
            </body>
            </html>
        `);
            win.document.close();
            win.print();
        }
    </script>
@endpush
