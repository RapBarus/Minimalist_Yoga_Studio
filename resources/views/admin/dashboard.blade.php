<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Minimalist Studio</title>

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
            --sidebar-w: 240px;
            --success: #27AE60;
            --warning: #F39C12;
            --danger: #C0392B;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--text);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
        }

        .sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .sidebar-logo img {
            width: 160px;
            height: auto;
            object-fit: contain;
            filter: brightness(0) invert(1);
            opacity: .9;
        }

        .sidebar-admin {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            font-size: .75rem;
            color: rgba(255, 255, 255, .5);
            letter-spacing: .05em;
        }

        .sidebar-admin strong {
            display: block;
            color: #fff;
            font-size: .85rem;
            margin-top: 2px;
        }

        .sidebar-nav {
            flex: 1;
            padding: 12px 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 20px;
            color: rgba(255, 255, 255, .6);
            text-decoration: none;
            font-size: .8rem;
            font-weight: 500;
            letter-spacing: .04em;
            transition: background .18s, color .18s;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, .06);
            color: #fff;
        }

        .nav-link.active {
            background: var(--clay);
            color: #fff;
        }

        .nav-link svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .nav-section {
            font-size: .62rem;
            font-weight: 600;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .3);
            padding: 16px 20px 6px;
        }

        .sidebar-logout {
            padding: 16px 20px;
            border-top: 1px solid rgba(255, 255, 255, .08);
        }

        .btn-sidebar-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 9px 14px;
            background: rgba(192, 57, 43, .2);
            color: #e87c6e;
            border: 1px solid rgba(192, 57, 43, .3);
            border-radius: 8px;
            font-family: 'Raleway', sans-serif;
            font-size: .78rem;
            font-weight: 500;
            cursor: pointer;
            transition: background .18s;
        }

        .btn-sidebar-logout:hover {
            background: rgba(192, 57, 43, .35);
        }

        .btn-sidebar-logout svg {
            width: 15px;
            height: 15px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ── Main content ── */
        .main {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: var(--bg-white);
            border-bottom: 1px solid var(--border);
            padding: 16px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .topbar-title {
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: .02em;
            color: var(--text);
        }

        .topbar-sub {
            font-size: .75rem;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .content {
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        /* ── Stat cards ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
        }

        @media (max-width: 1000px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .stat-card {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon svg {
            width: 22px;
            height: 22px;
            stroke: #fff;
            fill: none;
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .stat-icon.clay {
            background: var(--clay);
        }

        .stat-icon.blue {
            background: #4A7FA5;
        }

        .stat-icon.green {
            background: var(--success);
        }

        .stat-icon.amber {
            background: var(--warning);
        }

        .stat-number {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1;
        }

        .stat-label {
            font-size: .72rem;
            font-weight: 500;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-top: 3px;
        }

        /* ── Tables ── */
        .section-card {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .section-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-header-title {
            font-weight: 600;
            font-size: .88rem;
            letter-spacing: .02em;
        }

        .section-header-sub {
            font-size: .72rem;
            color: var(--text-muted);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            text-align: left;
            padding: 10px 20px;
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            background: #faf8f6;
        }

        td {
            padding: 12px 20px;
            font-size: .82rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #faf8f6;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: .68rem;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .badge-confirmed {
            background: #eafaf1;
            color: var(--success);
        }

        .badge-cancelled {
            background: #fdecea;
            color: var(--danger);
        }

        .badge-attended {
            background: #eaf3fb;
            color: #2980B9;
        }

        .badge-pending {
            background: #fef9e7;
            color: var(--warning);
        }

        /* Two col layout */
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 900px) {
            .two-col {
                grid-template-columns: 1fr;
            }
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .main {
            animation: fadeUp .4s ease both;
        }
    </style>
</head>

<body>

    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/minimalist-logo-2.png') }}" alt="Minimalist Studio">
        </div>

        <div class="sidebar-admin">
            Admin Panel
            <strong>{{ $admin_name }}</strong>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                <svg viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="14" y="14" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                </svg>
                Dashboard
            </a>
            <a href="#" class="nav-link">
                <svg viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                Member
            </a>
            <a href="#" class="nav-link">
                <svg viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                Jadwal
            </a>
            <a href="#" class="nav-link">
                <svg viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                </svg>
                Booking
            </a>

            <div class="nav-section">Konten</div>
            <a href="#" class="nav-link">
                <svg viewBox="0 0 24 24">
                    <polygon
                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                </svg>
                Penawaran
            </a>
            <a href="#" class="nav-link">
                <svg viewBox="0 0 24 24">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                    <polyline points="22,6 12,13 2,6" />
                </svg>
                Kelas
            </a>
        </nav>

        <div class="sidebar-logout">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-sidebar-logout">
                    <svg viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <div class="main">

        <div class="topbar">
            <div>
                <div class="topbar-title">Dashboard</div>
                <div class="topbar-sub">{{ now()->translatedFormat('l, d F Y') }}</div>
            </div>
        </div>

        <div class="content">

            {{-- Stats --}}
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon clay">
                        <svg viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                    </div>
                    <div>
                        <div class="stat-number">{{ $totalUsers }}</div>
                        <div class="stat-label">Total Member</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon blue">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                    </div>
                    <div>
                        <div class="stat-number">{{ $totalSchedules }}</div>
                        <div class="stat-label">Jadwal Aktif</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon green">
                        <svg viewBox="0 0 24 24">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="16" y1="13" x2="8" y2="13" />
                            <line x1="16" y1="17" x2="8" y2="17" />
                        </svg>
                    </div>
                    <div>
                        <div class="stat-number">{{ $totalBookings }}</div>
                        <div class="stat-label">Total Booking</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon amber">
                        <svg viewBox="0 0 24 24">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                        </svg>
                    </div>
                    <div>
                        <div class="stat-number">{{ $totalClasses }}</div>
                        <div class="stat-label">Jenis Kelas</div>
                    </div>
                </div>
            </div>

            {{-- Two column --}}
            <div class="two-col">

                {{-- Recent bookings --}}
                <div class="section-card">
                    <div class="section-header">
                        <div>
                            <div class="section-header-title">Booking Terbaru</div>
                            <div class="section-header-sub">10 transaksi terakhir</div>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Kelas</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                                <tr>
                                    <td>{{ $booking->user_name }}</td>
                                    <td>
                                        {{ $booking->class_name }}<br>
                                        <span
                                            style="font-size:.72rem;color:var(--text-muted)">{{ \Carbon\Carbon::parse($booking->schedule_date)->format('d M') }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $booking->status }}">{{ $booking->status }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada
                                        booking</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Upcoming schedules --}}
                <div class="section-card">
                    <div class="section-header">
                        <div>
                            <div class="section-header-title">Jadwal Mendatang</div>
                            <div class="section-header-sub">5 jadwal terdekat</div>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Kuota</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($upcomingSchedules as $schedule)
                                <tr>
                                    <td>
                                        {{ $schedule->class_name }}<br>
                                        <span
                                            style="font-size:.72rem;color:var(--text-muted)">{{ $schedule->coach_name }}</span>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d M') }}<br>
                                        <span
                                            style="font-size:.72rem;color:var(--text-muted)">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}</span>
                                    </td>
                                    <td>{{ $schedule->available_slots }}/{{ $schedule->capacity }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        style="text-align:center;color:var(--text-muted);padding:2rem;">Tidak ada
                                        jadwal</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
