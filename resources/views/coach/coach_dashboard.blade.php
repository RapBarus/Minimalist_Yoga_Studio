<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#3A2E28">
    <title>Coach Dashboard | Minimalist Studio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Raleway:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/minimalist-logo-2.png') }}" alt="Minimalist Studio">
        </div>
        <div class="sidebar-admin">
            Coach Panel
            <strong>{{ Session::get('user_name') }}</strong>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">Menu</div>
            <a href="{{ route('coach.dashboard') }}" class="nav-link active">
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
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                Jadwal Saya
            </a>
            <a href="#" class="nav-link">
                <svg viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                </svg>
                Member
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

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Dashboard Coach</div>
                <div class="topbar-sub">{{ now()->translatedFormat('l, d F Y') }}</div>
            </div>
            <button class="topbar-menu-btn" onclick="toggleSidebar()">
                <svg viewBox="0 0 24 24">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
        </div>

        <div class="content">

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon clay">
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
                    <div class="stat-icon blue">
                        <svg viewBox="0 0 24 24">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                        </svg>
                    </div>
                    <div>
                        <div class="stat-number">{{ $totalBookings }}</div>
                        <div class="stat-label">Total Booking</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">
                        <svg viewBox="0 0 24 24">
                            <polyline points="9 11 12 14 22 4" />
                            <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11" />
                        </svg>
                    </div>
                    <div>
                        <div class="stat-number">{{ $completedClasses }}</div>
                        <div class="stat-label">Kelas Selesai</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon amber">
                        <svg viewBox="0 0 24 24">
                            <line x1="12" y1="1" x2="12" y2="23" />
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                        </svg>
                    </div>
                    <div>
                        <div class="stat-number">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</div>
                        <div class="stat-label">Total Pendapatan</div>
                    </div>
                </div>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <div>
                        <div class="section-header-title">Jadwal Mendatang</div>
                        <div class="section-header-sub">Kelas yang akan datang</div>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>Peserta</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->class_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('d F Y') }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} –
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                                <td>{{ $schedule->capacity - $schedule->available_slots }} / {{ $schedule->capacity }}
                                </td>
                                <td>
                                    <span
                                        class="badge badge-{{ $schedule->available_slots > 0 ? 'confirmed' : 'inactive' }}">
                                        {{ $schedule->available_slots > 0 ? 'Tersedia' : 'Penuh' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem;">
                                    Tidak ada jadwal mendatang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('open');
        }
    </script>

</body>

</html>
