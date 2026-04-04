<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kelas | Minimalist Studio</title>
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
        <a href="{{ route('admin.promotions') }}" class="nav-link">
            <svg viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>Penawaran
        </a>
        <a href="{{ route('admin.classes') }}" class="nav-link active">
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
            <div class="topbar-title">Kelola Kelas</div>
            <div class="topbar-sub">Tambah dan kelola jenis kelas</div>
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

            {{-- Add class form --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-header-title">Tambah Kelas Baru</div>
                </div>
                <div style="padding:20px;">
                    <form action="{{ route('admin.classes.store') }}" method="POST" style="display:flex;flex-direction:column;gap:14px;">
                        @csrf
                        <div class="field">
                            <label>Nama Kelas</label>
                            <input type="text" name="class_name" placeholder="contoh: Yoga" value="{{ old('class_name') }}" required>
                        </div>
                        <div class="field">
                            <label>Deskripsi</label>
                            <textarea name="description" placeholder="Deskripsi kelas..." rows="3" style="width:100%;padding:.75rem .9rem;background:var(--bg-white);border:1.5px solid var(--border);border-radius:10px;font-family:'Raleway',sans-serif;font-size:.85rem;color:var(--text);outline:none;resize:vertical;">{{ old('description') }}</textarea>
                        </div>
                        <div class="field">
                            <label>Level</label>
                            <select name="level" required style="width:100%;padding:.75rem .9rem;background:var(--bg-white);border:1.5px solid var(--border);border-radius:10px;font-family:'Raleway',sans-serif;font-size:.85rem;color:var(--text);outline:none;">
                                <option value="">-- Pilih Level --</option>
                                <option value="beginner"     {{ old('level') === 'beginner'     ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced"     {{ old('level') === 'advanced'     ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Durasi (Menit)</label>
                            <input type="number" name="duration_minutes" placeholder="60" value="{{ old('duration_minutes') }}" min="1" required>
                        </div>
                        <button type="submit" class="btn-primary">Tambah Kelas</button>
                    </form>
                </div>
            </div>

            {{-- Classes table --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-header-title">Daftar Kelas</div>
                    <span class="badge badge-info">{{ $classes->count() }} kelas</span>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Level</th>
                            <th>Durasi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                            <tr>
                                <td>
                                    {{ $class->class_name }}<br>
                                    <span style="font-size:.72rem;color:var(--text-muted)">{{ Str::limit($class->description, 40) }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $class->level === 'beginner' ? 'confirmed' : ($class->level === 'intermediate' ? 'attended' : 'pending') }}">
                                        {{ $class->level }}
                                    </span>
                                </td>
                                <td>{{ $class->duration_minutes }} min</td>
                                <td>
                                    <form action="{{ route('admin.classes.destroy', $class->class_id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada kelas.</td></tr>
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
