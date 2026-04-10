@extends('layouts.app')

@section('title', 'Profil | Minimalist Studio')

@push('styles')
    <style>
        .content {
            padding: 32px 24px;
            max-width: 680px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .profile-hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 0 24px 28px;
            background: var(--bg-white);
            border-radius: 20px;
            border: 1.5px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .hero-banner {
            width: 100%;
            height: 80px;
            background: linear-gradient(135deg, var(--clay) 0%, #C4724A 100%);
            border-radius: 18px 18px 0 0;
            margin-bottom: -40px;
        }

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--clay-pale);
            border: 3px solid var(--bg-white);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            box-shadow: 0 4px 16px rgba(0, 0, 0, .12);
        }

        .avatar svg {
            width: 38px;
            height: 38px;
            stroke: var(--clay);
            fill: none;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .profile-name {
            font-weight: 700;
            font-size: 1.2rem;
            color: var(--text);
            letter-spacing: .02em;
        }

        .profile-role {
            font-size: .72rem;
            font-weight: 500;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--bg-white);
            background: var(--clay);
            padding: 3px 14px;
            border-radius: 20px;
        }

        .profile-phone {
            font-size: .8rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .profile-phone svg {
            width: 13px;
            height: 13px;
            stroke: var(--text-muted);
            fill: none;
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .stats-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10px;
        }

        .stat-card {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 18px 12px;
            text-align: center;
        }

        .stat-number {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 600;
            color: var(--clay);
            line-height: 1;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: .68rem;
            font-weight: 500;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .btn-logout {
            width: 100%;
            padding: .85rem;
            background: transparent;
            color: var(--danger);
            border: 1.5px solid var(--danger);
            border-radius: 12px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background .18s, color .18s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-logout:hover {
            background: var(--danger);
            color: #fff;
        }

        .btn-logout svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .info-input {
            font-size: .8rem;
            padding: 6px 8px;
            border-radius: 8px;
            border: 1.5px solid var(--border);
            width: 100%;
        }

        .save-btn {
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 6px 10px;
            cursor: pointer;
        }

        .edit-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
        }

        .edit-btn:hover {
            background: var(--clay-pale);
        }

        .edit-icon {
            width: 16px;
            height: 16px;
            stroke: var(--text-muted);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .editable.editing .edit-btn {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="content">

        <div class="profile-hero">
            <div class="hero-banner"></div>
            <div class="avatar">
                <svg viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </div>
            <div class="profile-name">{{ $user->name }}</div>
            <span class="profile-role">{{ ucfirst($user->role) }}</span>
            @if ($user->phone_number)
                <div class="profile-phone">
                    <svg viewBox="0 0 24 24">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.35 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 5.55 5.55l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg>
                    {{ $user->phone_number }}
                </div>
            @endif
        </div>

        {{-- <div class="stats-row">
            <div class="stat-card">
                <div class="stat-number">{{ $totalBookings }}</div>
                <div class="stat-label">Total Kelas</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $attendedBookings }}</div>
                <div class="stat-label">Hadir</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">{{ $upcomingBookings }}</div>
                <div class="stat-label">Mendatang</div>
            </div>
        </div> --}}

        <div class="info-card">
            <div class="info-card-title">Informasi Akun</div>

            <form action="{{ route('profile.update') }}" method="POST" class="info-row editable">
                @csrf
                @method('PUT')

                <div class="info-row-left">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>

                    <div>
                        <div class="info-label">Username</div>

                        <input type="text" name="name" value="{{ $user->name }}" class="info-input" hidden>

                        <div class="info-value value-text">{{ $user->name }}</div>
                    </div>
                </div>

                <button type="button" class="edit-btn">
                    <svg viewBox="0 0 24 24" class="edit-icon">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                    </svg>
                </button>

                <button type="submit" class="save-btn" hidden>✔</button>
            </form>

            <form action="{{ route('profile.update') }}" method="POST" class="info-row editable">
                @csrf
                @method('PUT')

                <div class="info-row-left">
                    <div class="info-icon"><svg viewBox="0 0 24 24">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.35 2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L7.91 8.6a16 16 0 0 0 5.55 5.55l.96-.96a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg></div>
                    <div>
                        <div class="info-label">Nomor HP</div>

                        <input type="text" name="phone_number" value="{{ $user->phone_number }}" class="info-input"
                            hidden>

                        <div class="info-value value-text">{{ $user->phone_number ?? '—' }}</div>
                    </div>
                </div>

                <button type="button" class="edit-btn">
                    <svg viewBox="0 0 24 24" class="edit-icon">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                    </svg>
                </button>

                <button type="submit" class="save-btn" hidden>✔</button>
            </form>

            <form action="{{ route('profile.update') }}" method="POST" class="info-row editable">
                @csrf
                @method('PUT')

                <div class="info-row-left">
                    <div class="info-icon">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </div>
                    <div style="width:100%;">
                        <div class="info-label">Password</div>

                        {{-- password baru --}}
                        <div class="input-wrap" style="position:relative;margin-bottom:6px;" hidden>
                            <input type="password" name="password" placeholder="Password baru" class="info-input pw-field"
                                id="pw-new">
                            <button type="button" class="eye-btn" onclick="togglePw('pw-new', this)"
                                style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0;color:#9A8C82;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6"
                                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>

                        {{-- konfirmasi --}}
                        <div class="input-wrap" style="position:relative;" hidden>
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi password"
                                class="info-input pw-field" id="pw-confirm">
                            <button type="button" class="eye-btn" onclick="togglePw('pw-confirm', this)"
                                style="position:absolute;right:8px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0;color:#9A8C82;">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.6"
                                    stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>

                        <div class="info-value value-text">••••••••</div>
                    </div>
                </div>

                <button type="button" class="edit-btn">
                    <svg viewBox="0 0 24 24" class="edit-icon">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                    </svg>
                </button>
                <button type="submit" class="save-btn" hidden>✔</button>
            </form>

            <div class="info-row">
                <div class="info-row-left">
                    <div class="info-icon"><svg viewBox="0 0 24 24">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                        </svg></div>
                    <div>
                        <div class="info-label">Status</div>
                        <div class="info-value">{{ ucfirst($user->status) }}</div>
                    </div>
                </div>
            </div>

            <div class="info-row">
                <div class="info-row-left">
                    <div class="info-icon"><svg viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg></div>
                    <div>
                        <div class="info-label">Bergabung Sejak</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('logout') }}" method="POST"> @csrf <button type="submit" class="btn-logout"> <svg
                    viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                    <polyline points="16 17 21 12 16 7" />
                    <line x1="21" y1="12" x2="9" y2="12" />
                </svg> Keluar </button> </form>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('.editable').forEach(row => {
                        const editBtn = row.querySelector('.edit-btn');
                        const saveBtn = row.querySelector('.save-btn');
                        const inputs = row.querySelectorAll('.info-input');
                        const valueText = row.querySelector('.value-text');

                        editBtn.addEventListener('click', () => {
                            row.classList.add('editing');
                            inputs.forEach(i => i.hidden = false);
                            valueText.hidden = true;
                            saveBtn.hidden = false;
                        });
                    });
                });
            </script>
            <script>
                document.querySelectorAll('.editable').forEach(row => {
                    const editBtn = row.querySelector('.edit-btn');
                    const saveBtn = row.querySelector('.save-btn');
                    const valueText = row.querySelector('.value-text');

                    if (!editBtn) return;

                    editBtn.addEventListener('click', () => {
                        row.classList.add('editing');
                        row.querySelectorAll('.info-input, .input-wrap').forEach(el => el.hidden = false);
                        if (valueText) valueText.hidden = true;
                        if (saveBtn) saveBtn.hidden = false;
                    });
                });
            </script>
            <script>
                function togglePw(id, btn) {
                    const input = document.getElementById(id);
                    input.type = input.type === 'password' ? 'text' : 'password';
                    btn.style.opacity = input.type === 'text' ? '1' : '.5';
                }
            </script>
        @endpush
    </div>

@endsection
