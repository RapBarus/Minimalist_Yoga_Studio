<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Coach | Minimalist Studio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Raleway:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/minimalist-logo-2.png') }}" alt="Minimalist Studio">
        </div>
        <div class="sidebar-admin">Admin Panel<strong>{{ Session::get('user_name') }}</strong></div>
        <nav class="sidebar-nav">
            <div class="nav-section">Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link"><svg viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" />
                    <rect x="14" y="3" width="7" height="7" />
                    <rect x="14" y="14" width="7" height="7" />
                    <rect x="3" y="14" width="7" height="7" />
                </svg>Dashboard</a>
            <a href="{{ route('admin.coaches') }}" class="nav-link active"><svg viewBox="0 0 24 24">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>Coach</a>
            <a href="#" class="nav-link"><svg viewBox="0 0 24 24">
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>Jadwal</a>
            <a href="#" class="nav-link"><svg viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                </svg>Booking</a>
            <div class="nav-section">Konten</div>
            <a href="#" class="nav-link"><svg viewBox="0 0 24 24">
                    <polygon
                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                </svg>Penawaran</a>
            <a href="#" class="nav-link"><svg viewBox="0 0 24 24">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                </svg>Kelas</a>
        </nav>
        <div class="sidebar-logout">
            <form action="{{ route('logout') }}" method="POST">@csrf
                <button type="submit" class="btn-sidebar-logout"><svg viewBox="0 0 24 24">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>Keluar</button>
            </form>
        </div>
    </aside>

    <div class="main">
        <div class="topbar">
            <div>
                <div class="topbar-title">Kelola Coach</div>
                <div class="topbar-sub">Tambah dan kelola akun coach</div>
            </div>
        </div>

        <div class="content">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <div class="two-col">

                {{-- Add coach form --}}
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-header-title">Tambah Coach Baru</div>
                    </div>
                    <div style="padding:20px;display:flex;flex-direction:column;gap:14px;">

                        <div class="login-hint">
                            Coach akan login dengan:<br>
                            <strong>namacoach@coach.com</strong>
                        </div>

                        <form action="{{ route('admin.coaches.store') }}" method="POST"
                            style="display:flex;flex-direction:column;gap:14px;">
                            @csrf
                            <div class="field">
                                <label>Nama / Username</label>
                                <input type="text" name="name" placeholder="contoh: nima"
                                    value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="field-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="field">
                                <label>Nomor HP</label>
                                <input type="tel" name="phone" placeholder="81234567890"
                                    value="{{ old('phone') }}" required>
                            </div>
                            <div class="field">
                                <label>Password</label>
                                <div style="position:relative;">
                                    <input type="password" name="password" id="coach-password"
                                        placeholder="Minimal 6 karakter" required style="padding-right:2.5rem;">
                                    <button type="button" onclick="toggleCoachPassword()"
                                        style="position:absolute;right:.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0;color:var(--text-muted);display:flex;align-items:center;">
                                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18"
                                            height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="field">
                                <label>Spesialisasi</label>
                                <input type="text" name="specialization" placeholder="contoh: Yoga, Zumba"
                                    value="{{ old('specialization') }}" required>
                            </div>
                            <div class="field-row">
                                <div class="field">
                                    <label>Tarif / Kelas (Rp)</label>
                                    <input type="number" name="rate_per_class" placeholder="150000"
                                        value="{{ old('rate_per_class') }}" min="0" required>
                                </div>
                                <div class="field">
                                    <label>Pengalaman (Tahun)</label>
                                    <input type="number" name="years_experience" placeholder="3"
                                        value="{{ old('years_experience') }}" min="0" required>
                                </div>
                            </div>
                            <button type="submit" class="btn-primary">Tambah Coach</button>
                        </form>
                    </div>
                </div>

                {{-- Coaches table --}}
                <div class="section-card">
                    <div class="section-header">
                        <div class="section-header-title">Daftar Coach</div>
                        <span class="badge badge-info">{{ $coaches->count() }} coach</span>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Spesialisasi</th>
                                <th>Tarif</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coaches as $coach)
                                <tr>
                                    <td>
                                        {{ $coach->name }}<br>
                                        <span
                                            style="font-size:.72rem;color:var(--text-muted)">{{ $coach->name }}@coach.com</span>
                                    </td>
                                    <td>{{ $coach->specialization }}</td>
                                    <td>Rp {{ number_format($coach->rate_per_class, 0, ',', '.') }}</td>
                                    <td><span class="badge badge-{{ $coach->status }}">{{ $coach->status }}</span>
                                    </td>
                                    <td>
                                        @if ($coach->status === 'active')
                                            <form action="{{ route('admin.coaches.destroy', $coach->coach_id) }}"
                                                method="POST" onsubmit="return confirm('Nonaktifkan coach ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger-sm">Nonaktifkan</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.coaches.restore', $coach->coach_id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn-restore-sm">Aktifkan</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada coach
                                        terdaftar.</td>
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

        function toggleCoachPassword() {
            const input = document.getElementById('coach-password');
            const icon = document.getElementById('eye-icon');
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.style.opacity = input.type === 'text' ? '1' : '.5';
        }
    </script>
</body>

</html>
