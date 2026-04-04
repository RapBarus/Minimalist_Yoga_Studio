@extends('layouts.app')

@section('title', 'Home | Minimalist Studio')

@push('styles')
    <style>
        .content {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* ── Promo card ── */
        .card-promo {
            flex: 0 0 300px;
            scroll-snap-align: start;
        }

        @media (min-width: 900px) {
            .card-promo {
                flex: 0 0 340px;
            }
        }

        .card-promo-title {
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .coach-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }

        .coach-avatar {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .7rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .promo-meta {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 10px;
        }

        .promo-meta-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .75rem;
            opacity: .9;
        }

        .promo-meta-row svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .price-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .price-old {
            font-size: .8rem;
            opacity: .65;
            text-decoration: line-through;
        }

        .price-arrow {
            font-size: .85rem;
            opacity: .7;
        }

        .price-new {
            font-weight: 700;
            font-size: 1.05rem;
        }

        .pertemuan-pill {
            margin-left: auto;
            font-size: .72rem;
            background: rgba(255, 255, 255, .15);
            padding: 3px 10px;
            border-radius: 20px;
            white-space: nowrap;
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

        /* ── Class cards ── */
        .card-class {
            background: var(--clay);
            border-radius: 16px;
            padding: 16px 18px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(160, 82, 45, .25);
        }

        .card-class::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .card-class-title {
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .card-class-meta {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 12px;
        }

        .card-class-meta-row {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: .78rem;
            opacity: .9;
        }

        .card-class-meta-row svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .card-class-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .card-class-price {
            font-weight: 700;
            font-size: .95rem;
        }

        .card-class-quota {
            font-size: .72rem;
            opacity: .8;
        }

        .quota-full {
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
            margin-top: 12px;
        }

        .btn-pesan:hover {
            background: rgba(255, 255, 255, .32);
        }

        .btn-pesan:disabled {
            opacity: .4;
            cursor: not-allowed;
        }
    </style>
@endpush

@section('content')
    <div class="content">

        {{-- Penawaran --}}
        <div class="section-label">Penawaran Special !!!</div>

        <div class="promo-scroll" id="slider">
            @forelse ($promotions as $promo)
                <div class="card-clay card-promo">

                    <div class="card-promo-title">{{ $promo->title }}</div>

                    {{-- Coach badge --}}
                    @if ($promo->coach_name)
                        <div class="coach-badge">
                            <div class="coach-avatar">{{ strtoupper(substr($promo->coach_name, 0, 1)) }}</div>
                            <span style="font-size:.78rem;opacity:.9;">{{ $promo->coach_name }}</span>
                        </div>
                    @endif

                    {{-- Date & time --}}
                    @if ($promo->schedule_date)
                        <div class="promo-meta">
                            <div class="promo-meta-row">
                                <svg viewBox="0 0 24 24">
                                    <rect x="3" y="4" width="18" height="18" rx="2" />
                                    <line x1="16" y1="2" x2="16" y2="6" />
                                    <line x1="8" y1="2" x2="8" y2="6" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                </svg>
                                {{ \Carbon\Carbon::parse($promo->schedule_date)->translatedFormat('l, d F Y') }}
                            </div>
                            @if ($promo->start_time)
                                <div class="promo-meta-row">
                                    <svg viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" />
                                        <polyline points="12 6 12 12 16 14" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($promo->start_time)->format('H:i') }} –
                                    {{ \Carbon\Carbon::parse($promo->end_time)->format('H:i') }} WIB
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Price row --}}
                    <div class="price-row">
                        <span class="price-old">Rp {{ $promo->original_price }}</span>
                        <span class="price-arrow">→</span>
                        <span class="price-new">Rp {{ $promo->promo_price }}</span>
                        @if ($promo->pertemuan)
                            <span class="pertemuan-pill">pertemuan : {{ $promo->pertemuan }}</span>
                        @endif
                    </div>

                    <a href="#" class="btn btn-white">Pesan Sekarang</a>
                </div>
            @empty
                <p style="color:var(--text-muted);font-size:.85rem;padding:1rem 0;">Tidak ada penawaran saat ini.</p>
            @endforelse
        </div>

        {{-- Jadwal Kelas --}}
        <div class="section-row">
            <div class="section-label">Jadwal Kelas</div>
            <span class="section-count" id="class-count">{{ $schedules->count() }} kelas tersedia</span>
        </div>

        {{-- Dropdown filters --}}
        <div class="filter-row">
            <div class="dropdown-wrap">
                <button class="dropdown-btn" id="btn-kelas" onclick="toggleDropdown('kelas')">
                    Kelas <svg viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="dropdown-menu" id="menu-kelas">
                    <div class="dropdown-label">Filter Kelas</div>
                    @foreach ($schedules->pluck('class_name')->unique() as $className)
                        <label class="dropdown-item">
                            <input type="checkbox" class="filter-check" data-type="kelas" value="{{ $className }}">
                            {{ $className }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="dropdown-wrap">
                <button class="dropdown-btn" id="btn-waktu" onclick="toggleDropdown('waktu')">
                    Waktu <svg viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="dropdown-menu" id="menu-waktu">
                    <div class="dropdown-label">Filter Hari</div>
                    @php $days = $schedules->map(fn($s) => \Carbon\Carbon::parse($s->schedule_date)->translatedFormat('l'))->unique()->values(); @endphp
                    @foreach ($days as $day)
                        <label class="dropdown-item">
                            <input type="checkbox" class="filter-check" data-type="waktu" value="{{ $day }}">
                            {{ $day }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="dropdown-wrap">
                <button class="dropdown-btn" id="btn-coach" onclick="toggleDropdown('coach')">
                    Coach <svg viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </button>
                <div class="dropdown-menu" id="menu-coach">
                    <div class="dropdown-label">Filter Coach</div>
                    @foreach ($schedules->pluck('coach_name')->unique() as $coachName)
                        <label class="dropdown-item">
                            <input type="checkbox" class="filter-check" data-type="coach" value="{{ $coachName }}">
                            {{ $coachName }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Class cards --}}
        <div class="cards-grid" id="cards-grid">
            @forelse ($schedules as $schedule)
                @php
                    $dayName = \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('l');
                    $initial = strtoupper(substr($schedule->coach_name, 0, 1));
                @endphp
                <div class="card-class" data-kelas="{{ $schedule->class_name }}" data-waktu="{{ $dayName }}"
                    data-coach="{{ $schedule->coach_name }}">

                    <div class="card-class-title">{{ $schedule->class_name }}</div>

                    <div class="coach-badge">
                        <div class="coach-avatar">{{ $initial }}</div>
                        <span style="font-size:.78rem;opacity:.9;">{{ $schedule->coach_name }}</span>
                    </div>

                    <div class="card-class-meta">
                        <div class="card-class-meta-row">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('l, d F Y') }}
                        </div>
                        <div class="card-class-meta-row">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} –
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                        </div>
                    </div>

                    <div class="card-class-footer">
                        <span class="card-class-price">Rp
                            {{ number_format($schedule->rate_per_class ?? 50000, 0, ',', '.') }}</span>
                        <span class="card-class-quota @if ($schedule->available_slots == 0) quota-full @endif">
                            @if ($schedule->available_slots > 0)
                                Kuota tersedia : {{ $schedule->available_slots }}
                            @else
                                Kuota penuh
                            @endif
                        </span>
                    </div>

                    @if ($schedule->available_slots > 0)
                        <a href="{{ route('payment.show', $schedule->schedule_id) }}" class="btn-pesan">Pesan
                            Sekarang</a>
                    @else
                        <button class="btn-pesan" disabled>Kuota Penuh</button>
                    @endif
                </div>
            @empty
                <div class="empty-state">Tidak ada jadwal kelas yang tersedia saat ini.</div>
            @endforelse
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        window.addEventListener('load', function() {

            // ── Promo carousel ──
            const slider = document.getElementById('slider');
            if (slider) {
                let isDown = false,
                    startX, scrollLeft, autoRotate;

                function startAutoRotate() {
                    autoRotate = setInterval(function() {
                        const card = slider.querySelector('.card-promo');
                        if (!card) return;
                        const maxScroll = slider.scrollWidth - slider.clientWidth;
                        slider.scrollLeft >= maxScroll - 2 ?
                            slider.scrollTo({
                                left: 0,
                                behavior: 'smooth'
                            }) :
                            slider.scrollBy({
                                left: card.offsetWidth + 14,
                                behavior: 'smooth'
                            });
                    }, 3000);
                }

                function stopAutoRotate() {
                    clearInterval(autoRotate);
                }

                startAutoRotate();
                slider.addEventListener('mouseenter', stopAutoRotate);
                slider.addEventListener('mouseleave', startAutoRotate);
                slider.addEventListener('touchstart', stopAutoRotate, {
                    passive: true
                });
                slider.addEventListener('touchend', () => setTimeout(startAutoRotate, 3000));
                slider.addEventListener('mousedown', function(e) {
                    isDown = true;
                    stopAutoRotate();
                    slider.style.cursor = 'grabbing';
                    startX = e.pageX - slider.getBoundingClientRect().left;
                    scrollLeft = slider.scrollLeft;
                    e.preventDefault();
                });
                document.addEventListener('mouseup', function() {
                    if (!isDown) return;
                    isDown = false;
                    slider.style.cursor = 'grab';
                    setTimeout(startAutoRotate, 3000);
                });
                document.addEventListener('mousemove', function(e) {
                    if (!isDown) return;
                    slider.scrollLeft = scrollLeft - (e.pageX - slider.getBoundingClientRect().left -
                        startX) * 1.5;
                });
            }

            // ── Dropdown filters ──
            const cards = document.querySelectorAll('.card-class');
            const countEl = document.getElementById('class-count');
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
                    if (this.checked) {
                        activeFilters[type].push(this.value);
                    } else {
                        activeFilters[type] = activeFilters[type].filter(v => v !== this.value);
                    }
                    document.getElementById('btn-' + type).classList.toggle('has-selection',
                        activeFilters[type].length > 0);
                    applyFilters();
                });
            });

            function applyFilters() {
                let visible = 0;
                cards.forEach(function(card) {
                    const show = (activeFilters.kelas.length === 0 || activeFilters.kelas.includes(card
                            .dataset.kelas)) &&
                        (activeFilters.waktu.length === 0 || activeFilters.waktu.includes(card.dataset
                            .waktu)) &&
                        (activeFilters.coach.length === 0 || activeFilters.coach.includes(card.dataset
                            .coach));
                    card.style.display = show ? '' : 'none';
                    if (show) visible++;
                });
                countEl.textContent = visible + ' kelas tersedia';
            }
        });
    </script>
@endpush
