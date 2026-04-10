@extends('layouts.app')

@section('title', 'Membership | Minimalist Studio')

@push('styles')
    <style>
        .content {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            padding-bottom: 90px;
        }

        /* ── Filter dropdowns ── */
        .filter-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .dropdown-wrap {
            position: relative;
        }

        .dropdown-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 20px;
            border: 1.5px solid var(--clay);
            background: transparent;
            color: var(--clay);
            font-family: 'Raleway', sans-serif;
            font-size: .75rem;
            font-weight: 500;
            letter-spacing: .04em;
            cursor: pointer;
            transition: background .18s, color .18s;
            white-space: nowrap;
        }

        .dropdown-btn svg {
            width: 12px;
            height: 12px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: transform .2s;
        }

        .dropdown-btn:hover,
        .dropdown-btn.has-selection {
            background: var(--clay);
            color: #fff;
        }

        .dropdown-btn.open svg {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            min-width: 160px;
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            z-index: 50;
            padding: 8px 0;
            animation: dropDown .15s ease both;
        }

        .dropdown-menu.open {
            display: block;
        }

        .dropdown-label {
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 6px 14px 4px;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 14px;
            font-size: .8rem;
            color: var(--text);
            cursor: pointer;
            transition: background .15s;
        }

        .dropdown-item:hover {
            background: var(--clay-pale);
        }

        .dropdown-item input[type="checkbox"] {
            width: 15px;
            height: 15px;
            accent-color: var(--clay);
            cursor: pointer;
            flex-shrink: 0;
        }

        @keyframes dropDown {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Member cards ── */
        .card-member {
            background: var(--clay);
            border-radius: 16px;
            padding: 16px 18px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(160, 82, 45, .25);
        }

        .card-member::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .card-member-title {
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .coach-badge-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
            text-decoration: none;
            color: inherit;
            border-radius: 20px;
            padding: 3px 10px 3px 3px;
            transition: background .18s;
            background: rgba(255, 255, 255, .15);
        }

        .coach-badge-link:hover {
            background: rgba(255, 255, 255, .25);
        }

        .coach-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .65rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .card-member-meta {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 10px;
        }

        .card-member-meta-row {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: .78rem;
            opacity: .9;
        }

        .card-member-meta-row svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .card-member-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 6px;
        }

        .price-group {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .price-old {
            font-size: .75rem;
            opacity: .65;
            text-decoration: line-through;
        }

        .price-new {
            font-weight: 700;
            font-size: .95rem;
        }

        .pertemuan-pill {
            font-size: .72rem;
            background: rgba(255, 255, 255, .15);
            padding: 3px 12px;
            border-radius: 20px;
            white-space: nowrap;
            font-weight: 600;
        }

        .btn-pesan {
            display: block;
            width: 100%;
            padding: .65rem 1rem;
            background: rgba(255, 255, 255, .2);
            color: #fff;
            border: 1.5px solid rgba(255, 255, 255, .4);
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            font-family: 'Raleway', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background .18s;
        }

        .btn-pesan:hover {
            background: rgba(255, 255, 255, .32);
        }

        .section-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .section-count {
            font-size: .72rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .empty-state {
            text-align: center;
            padding: 32px 0;
            color: var(--text-muted);
            font-size: .85rem;
        }
    </style>
@endpush

@section('content')
    <div class="content">

        {{-- Header row --}}
        <div class="section-row">
            <div class="section-label">Membership</div>
            <span class="section-count" id="member-count">{{ $promotions->count() }} paket tersedia</span>
        </div>

        {{-- Filters --}}
        <div class="filter-row">

            {{-- Kelas --}}
            <div class="dropdown-wrap">
                <button class="dropdown-btn" id="btn-kelas" onclick="toggleDropdown('kelas')">
                    Kelas <svg viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="dropdown-menu" id="menu-kelas">
                    <div class="dropdown-label">Filter Kelas</div>
                    @foreach ($promotions->pluck('title')->unique() as $title)
                        <label class="dropdown-item">
                            <input type="checkbox" class="filter-check" data-type="kelas" value="{{ $title }}">
                            {{ $title }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Waktu --}}
            <div class="dropdown-wrap">
                <button class="dropdown-btn" id="btn-waktu" onclick="toggleDropdown('waktu')">
                    Waktu <svg viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="dropdown-menu" id="menu-waktu">
                    <div class="dropdown-label">Filter Hari</div>
                    @php
                        $days = $promotions
                            ->filter(fn($p) => $p->schedule_date)
                            ->map(fn($p) => \Carbon\Carbon::parse($p->schedule_date)->translatedFormat('l'))
                            ->unique()
                            ->values();
                    @endphp
                    @foreach ($days as $day)
                        <label class="dropdown-item">
                            <input type="checkbox" class="filter-check" data-type="waktu" value="{{ $day }}">
                            {{ $day }}
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Coach --}}
            <div class="dropdown-wrap">
                <button class="dropdown-btn" id="btn-coach" onclick="toggleDropdown('coach')">
                    Coach <svg viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="dropdown-menu" id="menu-coach">
                    <div class="dropdown-label">Filter Coach</div>
                    @foreach ($promotions->pluck('coach_name')->filter()->unique() as $coachName)
                        <label class="dropdown-item">
                            <input type="checkbox" class="filter-check" data-type="coach" value="{{ $coachName }}">
                            {{ $coachName }}
                        </label>
                    @endforeach
                </div>
            </div>

        </div>

        {{-- Member package cards --}}
        <div id="cards-grid" style="display:flex;flex-direction:column;gap:14px;">
            @forelse ($promotions as $promo)
                @php
                    $dayName = $promo->schedule_date
                        ? \Carbon\Carbon::parse($promo->schedule_date)->translatedFormat('l')
                        : '';
                @endphp
                <div class="card-member" data-kelas="{{ $promo->title }}" data-waktu="{{ $dayName }}"
                    data-coach="{{ $promo->coach_name }}">

                    <div class="card-member-title">{{ $promo->title }}</div>

                    {{-- Coach badge --}}
                    @if ($promo->coach_name)
                        @if (!empty($promo->coach_id))
                            <a href="{{ route('coach.profile', $promo->coach_id) }}" class="coach-badge-link">
                                <div class="coach-avatar">{{ strtoupper(substr($promo->coach_name, 0, 1)) }}</div>
                                <span style="font-size:.75rem;">{{ $promo->coach_name }}</span>
                            </a>
                        @else
                            <div
                                style="display:inline-flex;align-items:center;gap:6px;margin-bottom:10px;background:rgba(255,255,255,.15);border-radius:20px;padding:3px 10px 3px 3px;">
                                <div class="coach-avatar">{{ strtoupper(substr($promo->coach_name, 0, 1)) }}</div>
                                <span style="font-size:.75rem;">{{ $promo->coach_name }}</span>
                            </div>
                        @endif
                    @endif

                    {{-- Date & time --}}
                    <div class="card-member-meta">
                        @if ($promo->schedule_date)
                            <div class="card-member-meta-row">
                                <svg viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                {{ \Carbon\Carbon::parse($promo->schedule_date)->translatedFormat('l, d F Y') }}
                            </div>
                        @endif
                        @if ($promo->start_time)
                            <div class="card-member-meta-row">
                                <svg viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                                {{ \Carbon\Carbon::parse($promo->start_time)->format('H:i') }} –
                                {{ \Carbon\Carbon::parse($promo->end_time)->format('H:i') }} WIB
                            </div>
                        @endif
                    </div>

                    {{-- Price & pertemuan --}}
                    <div class="card-member-footer">
                        <div class="price-group">
                            @if ($promo->original_price)
                                <span class="price-old">Rp {{ $promo->original_price }}</span>
                            @endif
                            <span class="price-new">Rp {{ $promo->promo_price }}</span>
                        </div>
                        @if ($promo->pertemuan)
                            <span class="pertemuan-pill">pertemuan : {{ $promo->pertemuan }}</span>
                        @endif
                    </div>

                    <a href="{{ route('payment.show', $promo->promo_id) }}" class="btn-pesan">Pesan Sekarang</a>
                </div>
            @empty
                <div class="empty-state">Tidak ada paket membership tersedia saat ini.</div>
            @endforelse
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        window.addEventListener('load', function() {

            const cards = document.querySelectorAll('.card-member');
            const countEl = document.getElementById('member-count');
            let activeFilters = {
                kelas: [],
                waktu: [],
                coach: []
            };

            window.toggleDropdown = function(type) {
                const menu = document.getElementById('menu-' + type);
                const btn = document.getElementById('btn-' + type);
                const isOpen = menu.classList.contains('open');
                document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('open'));
                document.querySelectorAll('.dropdown-btn').forEach(b => b.classList.remove('open'));
                if (!isOpen) {
                    menu.classList.add('open');
                    btn.classList.add('open');
                }
            };

            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown-wrap')) {
                    document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.remove('open'));
                    document.querySelectorAll('.dropdown-btn').forEach(b => b.classList.remove('open'));
                }
            });

            document.querySelectorAll('.filter-check').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const type = this.dataset.type;
                    this.checked ?
                        activeFilters[type].push(this.value) :
                        activeFilters[type] = activeFilters[type].filter(v => v !== this.value);
                    document.getElementById('btn-' + type)
                        .classList.toggle('has-selection', activeFilters[type].length > 0);
                    applyFilters();
                });
            });

            function applyFilters() {
                let visible = 0;
                cards.forEach(function(card) {
                    const show =
                        (activeFilters.kelas.length === 0 || activeFilters.kelas.includes(card.dataset
                            .kelas)) &&
                        (activeFilters.waktu.length === 0 || activeFilters.waktu.includes(card.dataset
                            .waktu)) &&
                        (activeFilters.coach.length === 0 || activeFilters.coach.includes(card.dataset
                            .coach));
                    card.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                countEl.textContent = visible + ' paket tersedia';
            }
        });
    </script>
@endpush
