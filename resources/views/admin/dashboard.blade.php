@extends('layouts.admin')

@section('title', 'Dashboard | Minimalist Studio')
@section('page-title', 'Menu Jadwal')
@section('page-sub', 'Kelola jadwal kelas studio')

@push('styles')
    <style>
        /* ── Calendar ── */
        .calendar-wrap {
            background: var(--bg-white);
            border-radius: 16px;
            border: 1.5px solid var(--border);
            padding: 20px;
        }

        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .calendar-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--text);
        }

        .cal-nav-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px 10px;
            border-radius: 8px;
            color: var(--clay);
            font-size: 1.2rem;
            transition: background .15s;
        }

        .cal-nav-btn:hover {
            background: var(--clay-pale);
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 3px;
        }

        .cal-day-label {
            text-align: center;
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 4px 0 8px;
        }

        .cal-day {
            text-align: center;
            padding: 6px 2px;
            border-radius: 8px;
            font-size: .78rem;
            color: var(--text);
            min-height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .cal-day.other-month {
            color: var(--text-muted);
            opacity: .35;
        }

        .cal-day.today {
            background: var(--clay);
            color: #fff;
            font-weight: 700;
        }

        .cal-day.has-schedule {
            background: rgba(160, 82, 45, .12);
            color: var(--clay);
            font-weight: 600;
        }

        .cal-day.today.has-schedule {
            background: var(--clay);
            color: #fff;
        }

        .cal-dot {
            position: absolute;
            bottom: 2px;
            left: 50%;
            transform: translateX(-50%);
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: var(--clay);
        }

        .cal-day.today .cal-dot {
            background: #fff;
        }

        /* ── Action buttons ── */
        .action-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .04em;
            cursor: pointer;
            transition: background .18s, transform .15s;
            border: none;
        }

        .btn-action:hover {
            transform: translateY(-1px);
        }

        .btn-action svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .btn-tambah-kelas {
            background: var(--clay);
            color: #fff;
        }

        .btn-tambah-member {
            background: #fff;
            color: var(--clay);
            border: 1.5px solid var(--clay) !important;
        }

        /* ── Schedule cards ── */
        .schedule-card {
            background: var(--clay);
            border-radius: 14px;
            padding: 16px 18px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .schedule-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .sc-title {
            font-weight: 700;
            font-size: .95rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .sc-coach {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255, 255, 255, .15);
            border-radius: 20px;
            padding: 3px 10px 3px 3px;
            font-size: .73rem;
            margin-bottom: 8px;
        }

        .sc-coach-avatar {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .6rem;
            font-weight: 700;
        }

        .sc-meta {
            display: flex;
            flex-direction: column;
            gap: 3px;
            margin-bottom: 10px;
        }

        .sc-meta-row {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: .75rem;
            opacity: .9;
        }

        .sc-meta-row svg {
            width: 12px;
            height: 12px;
            stroke: currentColor;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .sc-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .sc-price {
            font-weight: 700;
            font-size: .9rem;
        }

        .sc-quota {
            font-size: .72rem;
            opacity: .85;
        }

        .sc-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-view-jadwal {
            flex: 1;
            padding: .55rem;
            background: rgba(255, 255, 255, .2);
            color: #fff;
            border: 1.5px solid rgba(255, 255, 255, .35);
            border-radius: 8px;
            font-family: 'Raleway', sans-serif;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s;
        }

        .btn-view-jadwal:hover {
            background: rgba(255, 255, 255, .32);
        }

        .btn-hapus-jadwal {
            flex: 1;
            padding: .55rem;
            background: rgba(220, 38, 38, .25);
            color: #fff;
            border: 1.5px solid rgba(220, 38, 38, .4);
            border-radius: 8px;
            font-family: 'Raleway', sans-serif;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .06em;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-hapus-jadwal:hover {
            background: rgba(220, 38, 38, .4);
        }

        .empty-schedule {
            text-align: center;
            padding: 40px;
            color: var(--text-muted);
            font-size: .85rem;
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 14px;
        }

        .section-label-page {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        /* ── Modals ── */
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
                transform: translateY(16px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
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
        .modal-field select {
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
        .modal-field select:focus {
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
    </style>
@endpush

@section('content')
    <div class="content">

        {{-- Calendar --}}
        <div class="calendar-wrap">
            <div class="calendar-header">
                <button class="cal-nav-btn" onclick="changeMonth(-1)">&#8249;</button>
                <div class="calendar-title" id="cal-title"></div>
                <button class="cal-nav-btn" onclick="changeMonth(1)">&#8250;</button>
            </div>
            <div class="calendar-grid" id="cal-grid"></div>
        </div>

        {{-- Section label + action buttons --}}
        <div class="section-label-page">Jadwal Kelas</div>

        <div class="action-row">
            <button class="btn-action btn-tambah-member" onclick="openModal('modal-member')">
                <svg viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Tambah Member
            </button>
            <button class="btn-action btn-tambah-kelas" onclick="openModal('modal-kelas')">
                <svg viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Tambah Kelas
            </button>
        </div>

        {{-- Schedule cards --}}
        @forelse($schedules as $schedule)
            @php $initial = strtoupper(substr($schedule->coach_name, 0, 1)); @endphp
            <div class="schedule-card">
                <div class="sc-title">{{ $schedule->class_name }}</div>
                @if ($schedule->status === 'completed')
                    <span
                        style="display:inline-block;padding:2px 10px;background:rgba(255,255,255,.2);
                 border:1px solid rgba(255,255,255,.4);border-radius:20px;
                 font-size:.65rem;font-weight:600;letter-spacing:.06em;
                 margin-bottom:8px;">✓
                        SELESAI</span>
                @endif

                <div class="sc-coach">
                    <div class="sc-coach-avatar">{{ $initial }}</div>
                    {{ $schedule->coach_name }}
                </div>

                <div class="sc-meta">
                    <div class="sc-meta-row">
                        <svg viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <line x1="16" y1="2" x2="16" y2="6" />
                            <line x1="8" y1="2" x2="8" y2="6" />
                            <line x1="3" y1="10" x2="21" y2="10" />
                        </svg>
                        {{ \Carbon\Carbon::parse($schedule->schedule_date)->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="sc-meta-row">
                        <svg viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} –
                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                    </div>
                </div>

                <div class="sc-footer">
                    <span class="sc-price">Rp {{ number_format($schedule->rate_per_class ?? 0, 0, ',', '.') }}</span>
                    <span class="sc-quota">
                        @if ($schedule->available_slots > 0)
                            Kuota tersedia: {{ $schedule->available_slots }}
                        @else
                            Kuota penuh
                        @endif
                    </span>
                </div>

                <div class="sc-buttons">
                    <a href="{{ route('admin.schedules.view', $schedule->schedule_id) }}" class="btn-view-jadwal">View
                        Jadwal</a>
                    <form action="{{ route('admin.schedules.destroy', $schedule->schedule_id) }}" method="POST"
                        style="flex:1;" onsubmit="return confirm('Hapus jadwal ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-hapus-jadwal" style="width:100%;">Hapus Jadwal</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="empty-schedule">Belum ada jadwal kelas.</div>
        @endforelse

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
        document.querySelectorAll('.modal-overlay').forEach(o => o.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('open');
                document.body.style.overflow = '';
            }
        }));

        const scheduleDates = @json($scheduleDates);
        let currentDate = new Date();
        let currentYear = currentDate.getFullYear();
        let currentMonth = currentDate.getMonth();

        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
            'Oktober', 'November', 'Desember'
        ];
        const dayNames = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];

        function renderCalendar(year, month) {
            document.getElementById('cal-title').textContent = monthNames[month] + ' ' + year;
            const grid = document.getElementById('cal-grid');
            grid.innerHTML = '';

            dayNames.forEach(d => {
                const el = document.createElement('div');
                el.className = 'cal-day-label';
                el.textContent = d;
                grid.appendChild(el);
            });

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const prevDays = new Date(year, month, 0).getDate();
            const today = new Date();

            for (let i = firstDay - 1; i >= 0; i--) {
                const el = document.createElement('div');
                el.className = 'cal-day other-month';
                el.textContent = prevDays - i;
                grid.appendChild(el);
            }

            for (let d = 1; d <= daysInMonth; d++) {
                const el = document.createElement('div');
                el.className = 'cal-day';
                const dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
                if (d === today.getDate() && month === today.getMonth() && year === today.getFullYear()) el.classList.add(
                    'today');
                if (scheduleDates.includes(dateStr)) {
                    el.classList.add('has-schedule');
                    const dot = document.createElement('div');
                    dot.className = 'cal-dot';
                    el.appendChild(dot);
                }
                el.insertBefore(document.createTextNode(d), el.firstChild);
                grid.appendChild(el);
            }

            const total = firstDay + daysInMonth;
            const remaining = total % 7 === 0 ? 0 : 7 - (total % 7);
            for (let d = 1; d <= remaining; d++) {
                const el = document.createElement('div');
                el.className = 'cal-day other-month';
                el.textContent = d;
                grid.appendChild(el);
            }
        }

        function changeMonth(dir) {
            currentMonth += dir;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar(currentYear, currentMonth);
        }

        renderCalendar(currentYear, currentMonth);

        @if ($errors->has('class_id') || $errors->has('coach_id') || $errors->has('capacity'))
            openModal('modal-kelas');
        @endif
        @if ($errors->has('name') || $errors->has('quota_amount') || $errors->has('price'))
            openModal('modal-member');
        @endif
    </script>
@endpush

{{-- Modal: Tambah Kelas --}}
<div class="modal-overlay" id="modal-kelas">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Tambah Kelas</div>
            <button class="modal-close" onclick="closeModal('modal-kelas')">
                <svg viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.schedules.store') }}" method="POST" class="modal-form">
            @csrf
            <div class="modal-field">
                <label>Nama Kelas</label>
                <select name="class_id" required>
                    <option value="">Pilih Kelas</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->class_id }}"
                            {{ old('class_id') == $class->class_id ? 'selected' : '' }}>{{ $class->class_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="modal-field">
                <label>Coach</label>
                <select name="coach_id" required>
                    <option value="">Pilih Coach</option>
                    @foreach ($coaches as $coach)
                        <option value="{{ $coach->coach_id }}"
                            {{ old('coach_id') == $coach->coach_id ? 'selected' : '' }}>{{ $coach->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-field">
                <label>Tanggal</label>
                <input type="date" name="schedule_date" value="{{ old('schedule_date') }}"
                    min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="modal-field-row">
                <div class="modal-field">
                    <label>Jam Mulai</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}" required>
                </div>
                <div class="modal-field">
                    <label>Jam Selesai</label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}" required>
                </div>
            </div>
            <div class="modal-field">
                <label>Kuota Kelas</label>
                <input type="number" name="capacity" placeholder="Maks: 100" value="{{ old('capacity') }}"
                    min="1" max="100" required>
            </div>
            <button type="submit" class="btn-modal-submit">Tambah Kelas</button>
        </form>
    </div>
</div>

{{-- Modal: Tambah Member --}}
<div class="modal-overlay" id="modal-member">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Tambah Member</div>
            <button class="modal-close" onclick="closeModal('modal-member')">
                <svg viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.membership.store') }}" method="POST" class="modal-form">
            @csrf
            <div class="modal-field">
                <label>Nama Membership</label>
                <input type="text" name="name" placeholder="contoh: Member Yoga With Nima"
                    value="{{ old('name') }}" required>
            </div>
            <div class="modal-field">
                <label>Coach</label>
                <select name="coach_id">
                    <option value="">Pilih Coach</option>
                    @foreach ($coaches as $coach)
                        <option value="{{ $coach->coach_id }}">{{ $coach->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-field-row">
                <div class="modal-field">
                    <label>Jam Mulai</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}">
                </div>
                <div class="modal-field">
                    <label>Jam Selesai</label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}">
                </div>
            </div>
            <div class="modal-field-row">
                <div class="modal-field">
                    <label>Harga Asli (Rp)</label>
                    <input type="number" name="original_price" placeholder="150000"
                        value="{{ old('original_price') }}" min="0" step="1000">
                </div>
                <div class="modal-field">
                    <label>Harga Diskon (Rp)</label>
                    <input type="number" name="price" placeholder="120000" value="{{ old('price') }}"
                        min="0" step="1000" required>
                </div>
            </div>
            <div class="modal-field">
                <label>Kuota Kelas (Jumlah Sesi)</label>
                <input type="number" name="quota_amount" placeholder="8" value="{{ old('quota_amount') }}"
                    min="1" required>
            </div>
            <div class="modal-field">
                <label>Masa Aktif (Bulan)</label>
                <input type="number" name="validity_months" placeholder="2"
                    value="{{ old('validity_months', 2) }}" min="1" required>
            </div>
            <div class="modal-field">
                <label>Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Deskripsi paket..."
                    style="width:100%;padding:.72rem .9rem;background:#faf8f6;border:1.5px solid var(--border);border-radius:10px;font-family:'Raleway',sans-serif;font-size:.85rem;color:var(--text);outline:none;resize:vertical;">{{ old('description') }}</textarea>
            </div>
            <button type="submit" class="btn-modal-submit">Tambah Kelas</button>
        </form>
    </div>
</div>
