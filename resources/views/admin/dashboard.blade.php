<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Minimalist Studio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>



<body>
    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>
    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/minimalist-logo-2.png') }}" alt="Minimalist Studio">
        </div>

        <div class="sidebar-admin">Admin Panel<strong>{{ Session::get('user_name') }}</strong></div>
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
            <a href="{{ route('admin.coaches') }}" class="nav-link">
                <svg viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                Coach
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
            <button class="topbar-menu-btn" onclick="toggleSidebar()">
                <svg viewBox="0 0 24 24">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
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

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('open');
        }
    </script>
</body>

</html>
