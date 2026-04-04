<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Jadwal | Minimalist Studio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Raleway:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .status-select {
            padding: 4px 8px;
            border-radius: 8px;
            border: 1px solid var(--border);
            font-family: 'Raleway', sans-serif;
            font-size: .72rem;
            background: var(--bg);
            color: var(--text);
            cursor: pointer;
        }
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="overlay" onclick="toggleSidebar()"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/minimalist-logo-2.png') }}" alt="Minimalist Studio">
        </div>
        <div class="sidebar-admin">Admin Panel<strong>{{ Session::get('user_name') }}</strong></div>
        <nav class="sidebar-nav">
            <div class="nav-section">Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
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
            <a href="{{ route('admin.schedules') }}" class="nav-link active">
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
            <a href="{{ route('admin.promotions') }}" class="nav-link">
                <svg viewBox="0 0 24 24">
                    <polygon
                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                </svg>
                Penawaran
            </a>
            <a href="{{ route('admin.classes') }}" class="nav-link">
                <svg viewBox="0 0 24 24">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
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

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Kelola Jadwal</div>
                <div class="topbar-sub">Assign kelas dan coach ke jadwal</div>
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

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <div class="two-col">

                {{-- Add schedule form --}}
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-header-title">Tambah Jadwal Baru</div>
                    </div>
                    <div style="padding:20px;">
                        <form action="{{ route('admin.schedules.store') }}" method="POST"
                            style="display:flex;flex-direction:column;gap:14px;">
                            @csrf

                            <div class="field">
                                <label>Kelas</label>
                                <select name="class_id" required
                                    style="width:100%;padding:.75rem .9rem;background:var(--bg-white);border:1.5px solid var(--border);border-radius:10px;font-family:'Raleway',sans-serif;font-size:.85rem;color:var(--text);outline:none;">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->class_id }}"
                                            {{ old('class_id') == $class->class_id ? 'selected' : '' }}>
                                            {{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field">
                                <label>Coach</label>
                                <select name="coach_id" required
                                    style="width:100%;padding:.75rem .9rem;background:var(--bg-white);border:1.5px solid var(--border);border-radius:10px;font-family:'Raleway',sans-serif;font-size:.85rem;color:var(--text);outline:none;">
                                    <option value="">-- Pilih Coach --</option>
                                    @foreach ($coaches as $coach)
                                        <option value="{{ $coach->coach_id }}"
                                            {{ old('coach_id') == $coach->coach_id ? 'selected' : '' }}>
                                            {{ $coach->name }} — {{ $coach->specialization }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="field">
                                <label>Tanggal</label>
                                <input type="date" name="schedule_date" value="{{ old('schedule_date') }}"
                                    min="{{ date('Y-m-d') }}" required>
                            </div>

                            <div class="field-row">
                                <div class="field">
                                    <label>Waktu Mulai</label>
                                    <input type="time" name="start_time" value="{{ old('start_time') }}"
                                        required>
                                </div>
                                <div class="field">
                                    <label>Waktu Selesai</label>
                                    <input type="time" name="end_time" value="{{ old('end_time') }}" required>
                                </div>
                            </div>

                            <div class="field">
                                <label>Kapasitas</label>
                                <input type="number" name="capacity" placeholder="contoh: 15"
                                    value="{{ old('capacity') }}" min="1" required>
                            </div>

                            <button type="submit" class="btn-primary">Tambah Jadwal</button>
                        </form>
                    </div>
                </div>

                {{-- Schedules table --}}
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-header-title">Daftar Jadwal</div>
                        <span class="badge badge-info">{{ $schedules->count() }} jadwal</span>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Kelas</th>
                                <th>Coach</th>
                                <th>Tanggal</th>
                                <th>Kuota</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($schedules as $schedule)
                                <tr>
                                    <td>{{ $schedule->class_name }}</td>
                                    <td>{{ $schedule->coach_name }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y') }}<br>
                                        <span
                                            style="font-size:.72rem;color:var(--text-muted)">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                            – {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</span>
                                    </td>
                                    <td>{{ $schedule->available_slots }}/{{ $schedule->capacity }}</td>
                                    <td>
                                        <form action="{{ route('admin.schedules.status', $schedule->schedule_id) }}"
                                            method="POST">
                                            @csrf
                                            <select name="status" class="status-select"
                                                onchange="this.form.submit()">
                                                <option value="upcoming"
                                                    {{ $schedule->status === 'upcoming' ? 'selected' : '' }}>Upcoming
                                                </option>
                                                <option value="completed"
                                                    {{ $schedule->status === 'completed' ? 'selected' : '' }}>Completed
                                                </option>
                                                <option value="cancelled"
                                                    {{ $schedule->status === 'cancelled' ? 'selected' : '' }}>Cancelled
                                                </option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.schedules.destroy', $schedule->schedule_id) }}"
                                            method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-danger-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"
                                        style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada
                                        jadwal.</td>
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
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('overlay').classList.toggle('open');
        }
    </script>
</body>

</html>
