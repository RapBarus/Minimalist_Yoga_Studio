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

        /* Promo card */
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
            margin-bottom: 4px;
        }

        .card-promo-sub {
            font-size: .78rem;
            opacity: .85;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .price-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
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
            letter-spacing: .02em;
        }

        .pill-group {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            margin-bottom: 14px;
        }

        .pill {
            background: rgba(255, 255, 255, .18);
            border: 1px solid rgba(255, 255, 255, .30);
            color: #fff;
            padding: 5px 13px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 500;
            letter-spacing: .04em;
        }

        /* Class card */
        .card-class-title {
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: var(--blue-dark);
            margin-bottom: 4px;
        }

        .card-class-coach {
            font-size: .75rem;
            color: var(--blue-dark);
            opacity: .75;
            margin-bottom: 10px;
            font-style: italic;
        }

        .class-meta {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-bottom: 14px;
        }

        .class-meta-row {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: .8rem;
            color: #3A5A6A;
        }

        .class-meta-row svg {
            width: 14px;
            height: 14px;
            flex-shrink: 0;
            opacity: .7;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .class-quota {
            font-size: .75rem;
            color: #3A5A6A;
            margin-top: 2px;
            margin-bottom: 14px;
        }

        .quota-full {
            color: var(--danger);
            font-weight: 600;
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
                    <div class="card-promo-sub">{!! nl2br(e($promo->description)) !!}</div>
                    <div class="price-row">
                        <span class="price-old">{{ $promo->original_price }}</span>
                        <span class="price-arrow">→</span>
                        <span class="price-new">{{ $promo->promo_price }}</span>
                    </div>
                    <div class="pill-group">
                        @foreach (explode(',', $promo->tags) as $tag)
                            <span class="pill">{{ trim($tag) }}</span>
                        @endforeach
                    </div>
                    <a href="#" class="btn btn-white">Dapatkan Penawaran</a>
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

        <div class="filter-scroll">
            <button class="filter-pill active" data-filter="all">Semua</button>
            @foreach ($schedules->pluck('class_name')->unique() as $className)
                <button class="filter-pill" data-filter="{{ $className }}">{{ $className }}</button>
            @endforeach
        </div>

        <div class="cards-grid">
            @forelse ($schedules as $schedule)
                <div class="card-blue" data-class="{{ $schedule->class_name }}">
                    <div class="card-class-title">{{ $schedule->class_name }}</div>
                    <div class="card-class-coach">with {{ $schedule->coach_name }}</div>
                    <div class="class-meta">
                        <div class="class-meta-row">
                            <svg viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <line x1="16" y1="2" x2="16" y2="6" />
                                <line x1="8" y1="2" x2="8" y2="6" />
                                <line x1="3" y1="10" x2="21" y2="10" />
                            </svg>
                            {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('l, d F Y') }}
                        </div>
                        <div class="class-meta-row">
                            <svg viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} –
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                        </div>
                    </div>
                    <div class="class-quota @if ($schedule->available_slots == 0) quota-full @endif">
                        @if ($schedule->available_slots > 0)
                            Kuota tersedia: {{ $schedule->available_slots }} / {{ $schedule->capacity }}
                        @else
                            Kuota penuh
                        @endif
                    </div>
                    @if ($schedule->available_slots > 0)
                        <a href="{{ route('payment.show', $schedule->schedule_id) }}" class="btn btn-blue"
                            style="margin-top:14px;">Pesan Sekarang</a>
                    @else
                        <button class="btn btn-blue" style="margin-top:14px;opacity:.5;cursor:not-allowed;" disabled>Kuota
                            Penuh</button>
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
            const slider = document.getElementById('slider');
            let isDown = false,
                startX, scrollLeft, autoRotate;

            function startAutoRotate() {
                autoRotate = setInterval(function() {
                    const card = slider.querySelector('.card-promo');
                    const cardWidth = card.offsetWidth + 14;
                    const maxScroll = slider.scrollWidth - slider.clientWidth;
                    slider.scrollLeft >= maxScroll - 2 ?
                        slider.scrollTo({
                            left: 0,
                            behavior: 'smooth'
                        }) :
                        slider.scrollBy({
                            left: cardWidth,
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
                slider.scrollLeft = scrollLeft - (e.pageX - slider.getBoundingClientRect().left - startX) *
                    1.5;
            });

            // Filter
            const filterBtns = document.querySelectorAll('.filter-pill');
            const cards = document.querySelectorAll('.card-blue[data-class]');
            const countEl = document.getElementById('class-count');

            filterBtns.forEach(btn => btn.addEventListener('click', function() {
                filterBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                let visible = 0;
                cards.forEach(card => {
                    const match = btn.dataset.filter === 'all' || card.dataset.class === btn
                        .dataset.filter;
                    card.style.display = match ? '' : 'none';
                    if (match) visible++;
                });
                countEl.textContent = visible + ' kelas tersedia';
            }));
        });
    </script>
@endpush
