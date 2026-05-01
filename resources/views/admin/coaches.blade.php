@extends('layouts.admin')

@section('title', 'Data Coach | Minimalist Studio')
@section('page-title', 'Data Coach')
@section('page-sub', 'Kelola coach studio')

@push('styles')
    <style>
        .coach-list {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .coach-list-header {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }

        .coach-list-title {
            font-weight: 700;
            font-size: .95rem;
            color: var(--text);
        }

        .btn-tambah-coach {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .05em;
            cursor: pointer;
            transition: background .18s;
        }

        .btn-tambah-coach:hover {
            background: var(--clay-dark);
        }

        .btn-tambah-coach svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
        }

        .coach-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-bottom: 1px solid var(--border);
            transition: background .15s;
        }

        .coach-row:last-child {
            border-bottom: none;
        }

        .coach-row:hover {
            background: #faf8f6;
        }

        .coach-row-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .coach-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: var(--clay-pale);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            font-weight: 700;
            color: var(--clay);
            flex-shrink: 0;
        }

        .coach-name {
            font-size: .85rem;
            font-weight: 600;
            color: var(--text);
        }

        .coach-spec {
            font-size: .72rem;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .coach-row-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-edit-coach {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            color: var(--clay);
            transition: background .15s;
        }

        .btn-edit-coach:hover {
            background: var(--clay-pale);
        }

        .btn-edit-coach svg {
            width: 15px;
            height: 15px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal-overlay.open {
            display: flex;
        }

        .modal {
            background: #fff;
            border-radius: 18px;
            width: 100%;
            max-width: 420px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 24px;
            animation: modalIn .2s ease both;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .modal-title {
            font-weight: 700;
            font-size: 1rem;
            color: var(--text);
        }

        .modal-close {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: var(--text-muted);
            border-radius: 6px;
        }

        .modal-close svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        .modal-form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .modal-field label {
            display: block;
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: .35rem;
        }

        .modal-field input,
        .modal-field select,
        .modal-field textarea {
            width: 100%;
            padding: .72rem .9rem;
            background: #faf8f6;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            color: var(--text);
            outline: none;
            transition: border-color .2s;
        }

        .modal-field input:focus,
        .modal-field select:focus,
        .modal-field textarea:focus {
            border-color: var(--clay);
        }

        .modal-field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .btn-modal-submit {
            width: 100%;
            padding: .85rem;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 4px;
            transition: background .18s;
        }

        .btn-modal-submit:hover {
            background: var(--clay-dark);
        }

        .login-hint {
            font-size: .75rem;
            color: var(--text-muted);
            background: var(--clay-pale);
            border-radius: 8px;
            padding: 10px 12px;
            line-height: 1.6;
        }

        .login-hint strong {
            color: var(--clay);
        }
    </style>
@endpush

@section('content')
    <div class="content">

        <div class="coach-list">
            <div class="coach-list-header">
                <div class="coach-list-title">Coach</div>
                <button class="btn-tambah-coach" onclick="openModal('modal-tambah-coach')">
                    <svg viewBox="0 0 24 24">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Tambah Coach
                </button>
            </div>

            @forelse($coaches as $coach)
                <div class="coach-row">
                    <div class="coach-row-left">
                        <div class="coach-avatar">{{ strtoupper(substr($coach->name, 0, 1)) }}</div>
                        <div>
                            <div class="coach-name">{{ $coach->name }}</div>
                            <div class="coach-spec">{{ $coach->specialization ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="coach-row-actions">
                        @if ($coach->status === 'inactive')
                            <span class="badge badge-cancelled" style="margin-right:4px;">Nonaktif</span>
                            <form action="{{ route('admin.coaches.restore', $coach->coach_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-restore-sm">Aktifkan</button>
                            </form>
                        @else
                            <a href="{{ route('admin.coaches.detail', $coach->coach_id) }}" class="btn-edit-coach">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.coaches.destroy', $coach->coach_id) }}" method="POST"
                                onsubmit="return confirm('Nonaktifkan coach ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger-sm">Nonaktifkan</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align:center;padding:2rem;color:var(--text-muted);font-size:.85rem;">
                    Belum ada coach.
                </div>
            @endforelse
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('open');
                    document.body.style.overflow = '';
                }
            });
        });

        @if ($errors->any())
            openModal('modal-tambah-coach');
        @endif

        function togglePassword() {
            const input = document.getElementById('coach-password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML =
                    '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>';
            } else {
                input.type = 'password';
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
            }
        }
    </script>
@endpush

<div class="modal-overlay" id="modal-tambah-coach">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Tambah Coach</div>
            <button class="modal-close" onclick="closeModal('modal-tambah-coach')">
                <svg viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.coaches.store') }}" method="POST" class="modal-form">
            @csrf

            <div class="modal-field">
                <label>Nama Coach</label>
                <input type="text" name="name" placeholder="Masukan Nama Coach" value="{{ old('name') }}"
                    required>
            </div>

            <div class="modal-field">
                <label>Keahlian (Specialization)</literal>
                    <input type="text" name="specialization" placeholder="contoh: Yoga, Zumba"
                        value="{{ old('specialization') }}">
            </div>

            <div class="modal-field">
                <label>Nomor HP</label>
                <input type="text" name="phone" placeholder="contoh: +628123456789 atau 08123456789"
                    value="{{ old('phone') }}" required>
            </div>

            <div class="modal-field">
                <label>Deskripsi</label>
                <textarea name="bio" rows="3" placeholder="Masukan Deskripsi">{{ old('bio') }}</textarea>
            </div>

            <div class="modal-field-row">
                <div class="modal-field">
                    <label>Rate per Kelas (Rp)</label>
                    <input type="number" name="rate_per_class" placeholder="50000" value="{{ old('rate_per_class') }}"
                        min="0" step="1000">
                </div>
                <div class="modal-field">
                    <label>Pengalaman (Tahun)</label>
                    <input type="number" name="years_experience" placeholder="0"
                        value="{{ old('years_experience', 0) }}" min="0">
                </div>
            </div>

            <div class="modal-field">
                <label>Password</label>
                <div style="position:relative;">
                    <input type="password" name="password" id="coach-password" placeholder="Min. 6 karakter" required
                        style="padding-right:2.5rem;">
                    <button type="button" onclick="togglePassword()"
                        style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:4px;color:var(--text-muted);">
                        <svg id="eye-icon" viewBox="0 0 24 24" width="16" height="16" stroke="currentColor"
                            fill="none" stroke-width="2" stroke-linecap="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="login-hint">
                Login coach:
                <strong>{{ old('name') ? strtolower(str_replace(' ', '', old('name'))) : 'namacoach' }}@coach.com</strong>
            </div>

            <button type="submit" class="btn-modal-submit">Tambah Coach</button>
        </form>
    </div>
</div>
