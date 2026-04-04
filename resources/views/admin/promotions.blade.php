<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Penawaran | Minimalist Studio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
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
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard
        </a>
        <a href="{{ route('admin.coaches') }}" class="nav-link">
            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>Coach
        </a>
        <a href="{{ route('admin.schedules') }}" class="nav-link">
            <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>Jadwal
        </a>
        <a href="#" class="nav-link">
            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Booking
        </a>
        <div class="nav-section">Konten</div>
        <a href="{{ route('admin.promotions') }}" class="nav-link active">
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>Penawaran
        </a>
        <a href="{{ route('admin.classes') }}" class="nav-link">
            <svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>Kelas
        </a>
    </nav>
    <div class="sidebar-logout">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-sidebar-logout">
                <svg viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Keluar
            </button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div>
            <div class="topbar-title">Kelola Penawaran</div>
            <div class="topbar-sub">Tambah dan kelola penawaran spesial</div>
        </div>
        <button class="topbar-menu-btn" onclick="toggleSidebar()">
            <svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
        </button>
    </div>

    <div class="content">

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">{{ $errors->first() }}</div>
        @endif

        <div class="two-col">

            {{-- Add promotion form --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-header-title">Tambah Penawaran Baru</div>
                </div>
                <div style="padding:20px;">
                    <form action="{{ route('admin.promotions.store') }}" method="POST" style="display:flex;flex-direction:column;gap:14px;">
                        @csrf

                        <div class="field">
                            <label>Judul</label>
                            <input type="text" name="title" placeholder="contoh: Member Yoga with Nima" value="{{ old('title') }}" required>
                        </div>

                        <div class="field">
                            <label>Deskripsi</label>
                            <textarea name="description" placeholder="Deskripsi penawaran..." rows="2" style="width:100%;padding:.75rem .9rem;background:var(--bg-white);border:1.5px solid var(--border);border-radius:10px;font-family:'Raleway',sans-serif;font-size:.85rem;color:var(--text);outline:none;resize:vertical;">{{ old('description') }}</textarea>
                        </div>

                        <div class="field-row">
                            <div class="field">
                                <label>Harga Asli</label>
                                <input type="text" name="original_price" placeholder="150000" value="{{ old('original_price') }}" required>
                            </div>
                            <div class="field">
                                <label>Harga Promo</label>
                                <input type="text" name="promo_price" placeholder="90000" value="{{ old('promo_price') }}" required>
                            </div>
                        </div>

                        <div class="field">
                            <label>Coach</label>
                            <select name="coach_name" style="width:100%;padding:.75rem .9rem;background:var(--bg-white);border:1.5px solid var(--border);border-radius:10px;font-family:'Raleway',sans-serif;font-size:.85rem;color:var(--text);outline:none;">
                                <option value="">-- Pilih Coach (opsional) --</option>
                                @foreach($coaches as $coach)
                                    <option value="{{ $coach->name }}" {{ old('coach_name') === $coach->name ? 'selected' : '' }}>{{ $coach->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label>Tanggal</label>
                            <input type="date" name="schedule_date" value="{{ old('schedule_date') }}">
                        </div>

                        <div class="field-row">
                            <div class="field">
                                <label>Waktu Mulai</label>
                                <input type="time" name="start_time" value="{{ old('start_time') }}">
                            </div>
                            <div class="field">
                                <label>Waktu Selesai</label>
                                <input type="time" name="end_time" value="{{ old('end_time') }}">
                            </div>
                        </div>

                        <div class="field-row">
                            <div class="field">
                                <label>Pertemuan</label>
                                <input type="number" name="pertemuan" placeholder="8" value="{{ old('pertemuan') }}" min="1">
                            </div>
                            <div class="field">
                                <label>Tags</label>
                                <input type="text" name="tags" placeholder="Yoga,Zumba" value="{{ old('tags') }}">
                            </div>
                        </div>

                        <button type="submit" class="btn-primary">Tambah Penawaran</button>
                    </form>
                </div>
            </div>

            {{-- Promotions table --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-header-title">Daftar Penawaran</div>
                    <span class="badge badge-info">{{ $promotions->count() }} penawaran</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Harga</th>
                            <th>Coach</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promotions as $promo)
                            <tr>
                                <td>
                                    {{ $promo->title }}<br>
                                    @if($promo->schedule_date)
                                        <span style="font-size:.72rem;color:var(--text-muted)">
                                            {{ \Carbon\Carbon::parse($promo->schedule_date)->format('d M Y') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span style="text-decoration:line-through;opacity:.6;font-size:.75rem;">{{ $promo->original_price }}</span><br>
                                    <strong>{{ $promo->promo_price }}</strong>
                                </td>
                                <td>{{ $promo->coach_name ?? '—' }}</td>
                                <td>
                                    <span class="badge badge-{{ $promo->is_active ? 'confirmed' : 'inactive' }}">
                                        {{ $promo->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td style="display:flex;gap:6px;flex-wrap:wrap;">
                                    <form action="{{ route('admin.promotions.toggle', $promo->promo_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="{{ $promo->is_active ? 'btn-danger-sm' : 'btn-restore-sm' }}">
                                            {{ $promo->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.promotions.destroy', $promo->promo_id) }}" method="POST" onsubmit="return confirm('Hapus penawaran ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada penawaran.</td></tr>
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
