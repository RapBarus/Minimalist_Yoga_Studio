<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F2EFEB">
    <title>Home | Minimalist Studio</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;600&family=Raleway:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --clay: #A0522D;
            --clay-dark: #8B4513;
            --clay-light: #C4724A;
            --clay-pale: #F0E6DF;
            --blue-card: #C8DFF0;
            --blue-btn: #4A7FA5;
            --blue-dark: #3A6A8A;
            --bg: #F2EFEB;
            --text: #3A2E28;
            --text-muted: #9A8C82;
            --border: #E0D8D0;
            --navbar-h: 68px;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        /* ── Page wrapper ── */
        .page {
            max-width: 680px;
            margin: 0 auto;
            padding: 0 0 calc(var(--navbar-h) + 12px);
            animation: fadeUp .5s ease both;
            min-height: 100vh;
        }

        /* ── Header ── */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px 10px;
            background: var(--bg);
            position: sticky;
            top: 0;
            z-index: 10;
            border-bottom: 1px solid var(--border);
        }

        .header img {
            width: 260px;
            height: auto;
            object-fit: contain;
        }

        .header-greeting {
            font-size: .75rem;
            color: var(--text-muted);
            letter-spacing: .05em;
            text-align: right;
        }

        .header-greeting strong {
            display: block;
            font-size: .85rem;
            font-weight: 600;
            color: var(--text);
            letter-spacing: .02em;
        }

        /* ── Scroll content ── */
        .content {
            padding: 18px 16px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* ── Section label ── */
        .section-label {
            font-family: 'Raleway', sans-serif;
            font-weight: 700;
            font-size: .95rem;
            letter-spacing: .06em;
            color: var(--clay);
            text-transform: uppercase;
            margin-bottom: -4px;
        }

        /* ── Promo carousel wrapper ── */
        .promo-wrapper {
            position: relative;
        }

        /* ── Promo carousel ── */
        .promo-scroll {
            display: flex;
            gap: 12px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
            margin: 0 -16px;
            padding: 0 16px 8px;
            user-select: none;
            -webkit-user-select: none;
            cursor: grab;
            scroll-behavior: smooth;
        }

        .promo-scroll::-webkit-scrollbar {
            display: none;
        }

        /* ── Promo card (clay) ── */
        .card-promo {
            background: var(--clay);
            border-radius: 18px;
            padding: 18px 18px 16px;
            color: #fff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 6px 24px rgba(160, 82, 45, .30);
            flex: 0 0 300px;
            scroll-snap-align: start;
        }

        .card-promo::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 140px;
            height: 140px;
            background: rgba(255, 255, 255, .07);
            border-radius: 50%;
        }

        .card-promo::after {
            content: '';
            position: absolute;
            bottom: -20px;
            right: 40px;
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, .05);
            border-radius: 50%;
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

        /* ── Category pills ── */
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
            backdrop-filter: blur(4px);
            cursor: pointer;
            transition: background .18s;
        }

        .pill:hover,
        .pill.active {
            background: rgba(255, 255, 255, .32);
        }

        /* ── Buttons ── */
        .btn {
            display: inline-block;
            width: 100%;
            padding: .7rem 1rem;
            border-radius: 10px;
            border: none;
            font-family: 'Raleway', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .12em;
            text-transform: uppercase;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: transform .18s, box-shadow .18s, background .18s;
        }

        .btn-white {
            background: #fff;
            color: var(--clay);
            box-shadow: 0 2px 10px rgba(0, 0, 0, .12);
        }

        .btn-white:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, .16);
        }

        .btn-blue {
            background: var(--blue-btn);
            color: #fff;
            box-shadow: 0 4px 14px rgba(74, 127, 165, .30);
        }

        .btn-blue:hover {
            background: var(--blue-dark);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(74, 127, 165, .38);
        }

        .btn:active {
            transform: translateY(0);
        }

        /* ── Class card (blue) ── */
        .card-class {
            background: var(--blue-card);
            border-radius: 18px;
            padding: 18px 18px 16px;
            color: var(--text);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(74, 127, 165, .18);
        }

        .card-class::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 110px;
            height: 110px;
            background: rgba(255, 255, 255, .25);
            border-radius: 50%;
        }

        .card-class-title {
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: var(--blue-dark);
            margin-bottom: 10px;
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
        }

        .class-price {
            font-weight: 700;
            font-size: 1rem;
            color: var(--blue-dark);
        }

        .class-quota {
            font-size: .75rem;
            color: #3A5A6A;
            margin-top: 2px;
            margin-bottom: 14px;
        }

        /* ── Bottom navbar ── */
        .navbar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: var(--navbar-h);
            background: var(--bg);
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-around;
            z-index: 100;
            box-shadow: 0 -4px 20px rgba(0, 0, 0, .06);
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: .65rem;
            font-weight: 500;
            letter-spacing: .06em;
            text-transform: uppercase;
            transition: color .18s;
            padding: 8px 16px;
        }

        .nav-item svg {
            width: 22px;
            height: 22px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.6;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .nav-item.active {
            color: var(--clay);
        }

        .nav-item.active svg {
            stroke: var(--clay);
        }

        /* Active home pill bg */
        .nav-item.active .nav-pill {
            background: var(--clay-pale);
            border-radius: 20px;
            padding: 6px 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="page">

        {{-- Header --}}
        <div class="header">
            <img src="{{ asset('images/minimalist-logo-2.png') }}" alt="Minimalist Studio">
            <div class="header-greeting">
                Selamat datang
                <strong>Minimalist Studio</strong>
            </div>
        </div>

        <div class="content">

            {{-- Special offer label --}}
            <div class="section-label">Penawaran Special !!!</div>

            {{-- Promo carousel --}}
            <div class="promo-wrapper">
                <div class="promo-scroll" id="slider">

                    {{-- Promo 1 --}}
                    <div class="card-promo">
                        <div class="card-promo-title">Member Zumba with Nima</div>
                        <div class="card-promo-sub">Setiap Selasa jam 12:00–14:00<br>Member untuk 8x pertemuan</div>
                        <div class="price-row">
                            <span class="price-old">200K</span>
                            <span class="price-arrow">→</span>
                            <span class="price-new">150K</span>
                        </div>
                        <div class="pill-group">
                            <span class="pill active">Zumba</span>
                            <span class="pill">8x Pertemuan</span>
                        </div>
                        <a href="#" class="btn btn-white">Dapatkan Penawaran</a>
                    </div>

                    {{-- Promo 2 --}}
                    <div class="card-promo" style="background: #8B4513;">
                        <div class="card-promo-title">Paket Yoga Bulanan</div>
                        <div class="card-promo-sub">Akses tak terbatas selama 1 bulan<br>Semua level diterima</div>
                        <div class="price-row">
                            <span class="price-old">500K</span>
                            <span class="price-arrow">→</span>
                            <span class="price-new">350K</span>
                        </div>
                        <div class="pill-group">
                            <span class="pill">Yoga</span>
                            <span class="pill">Unlimited</span>
                        </div>
                        <a href="#" class="btn btn-white">Dapatkan Penawaran</a>
                    </div>

                    {{-- Promo 3 --}}
                    <div class="card-promo" style="background: #B5651D;">
                        <div class="card-promo-title">Paket Poundfit Duo</div>
                        <div class="card-promo-sub">Daftar berdua, hemat lebih banyak<br>Berlaku hingga akhir bulan
                        </div>
                        <div class="price-row">
                            <span class="price-old">300K</span>
                            <span class="price-arrow">→</span>
                            <span class="price-new">220K</span>
                        </div>
                        <div class="pill-group">
                            <span class="pill">Poundfit</span>
                            <span class="pill">Berdua</span>
                        </div>
                        <a href="#" class="btn btn-white">Dapatkan Penawaran</a>
                    </div>

                    {{-- Promo 4 --}}
                    <div class="card-promo" style="background: #6B3A2A;">
                        <div class="card-promo-title">Member Aerobik 3 Bulan</div>
                        <div class="card-promo-sub">Hemat lebih dengan paket 3 bulan<br>Setiap Senin & Rabu 07:00–08:00
                        </div>
                        <div class="price-row">
                            <span class="price-old">750K</span>
                            <span class="price-arrow">→</span>
                            <span class="price-new">550K</span>
                        </div>
                        <div class="pill-group">
                            <span class="pill">Aerobik</span>
                            <span class="pill">3 Bulan</span>
                        </div>
                        <a href="#" class="btn btn-white">Dapatkan Penawaran</a>
                    </div>

                    {{-- Promo 5 --}}
                    <div class="card-promo" style="background: #C4724A;">
                        <div class="card-promo-title">Free Trial Kelas Baru</div>
                        <div class="card-promo-sub">Coba 1 kelas gratis untuk member baru<br>Pilih kelas favoritmu</div>
                        <div class="price-row">
                            <span class="price-old">75K</span>
                            <span class="price-arrow">→</span>
                            <span class="price-new">GRATIS</span>
                        </div>
                        <div class="pill-group">
                            <span class="pill">Yoga</span>
                            <span class="pill">Zumba</span>
                            <span class="pill">Aerobik</span>
                        </div>
                        <a href="#" class="btn btn-white">Dapatkan Penawaran</a>
                    </div>

                </div>{{-- end promo-scroll --}}
            </div>{{-- end promo-wrapper --}}

            {{-- Class card --}}
            <div class="card-class">
                <div class="card-class-title">Yoga with Nima</div>

                <div class="class-meta">
                    <div class="class-meta-row">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        Selasa, 20 Maret 2026
                    </div>
                    <div class="class-meta-row">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        09:00 – 11:00 WIB
                    </div>
                </div>

                <div class="class-price">Rp 50.000</div>
                <div class="class-quota">Kuota tersedia: 15</div>

                <a href="#" class="btn btn-blue">Pesan Sekarang</a>
            </div>

        </div>
    </div>

    {{-- Bottom navbar --}}
    <nav class="navbar">
        <a href="{{ route('home') }}" class="nav-item active">
            <div class="nav-pill">
                <svg viewBox="0 0 24 24">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                Home
            </div>
        </a>

        <a href="{{ route('activity') }}" class="nav-item">
            <svg viewBox="0 0 24 24">
                <rect x="3" y="4" width="18" height="18" rx="2" />
                <line x1="16" y1="2" x2="16" y2="6" />
                <line x1="8" y1="2" x2="8" y2="6" />
                <line x1="3" y1="10" x2="21" y2="10" />
            </svg>
            Aktivitas
        </a>

        <a href="#" class="nav-item">
            <svg viewBox="0 0 24 24">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
            Profil
        </a>
    </nav>

    <script>
        window.addEventListener('load', function() {
            const slider = document.getElementById('slider');
            let isDown = false,
                startX, scrollLeft;

            slider.addEventListener('mousedown', function(e) {
                isDown = true;
                slider.style.cursor = 'grabbing';
                startX = e.pageX - slider.getBoundingClientRect().left;
                scrollLeft = slider.scrollLeft;
                e.preventDefault();
            });

            document.addEventListener('mouseup', function() {
                isDown = false;
                slider.style.cursor = 'grab';
            });

            document.addEventListener('mousemove', function(e) {
                if (!isDown) return;
                const x = e.pageX - slider.getBoundingClientRect().left;
                slider.scrollLeft = scrollLeft - (x - startX) * 1.5;
            });
        });
    </script>
</body>

</html>
