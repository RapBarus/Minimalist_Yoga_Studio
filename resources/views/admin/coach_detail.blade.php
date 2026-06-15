@extends('layouts.admin')

@section('title', 'Data Coach | Minimalist Studio')
@section('page-title', 'Data Coach')
@section('page-sub', 'Detail dan riwayat coach')

@push('styles')
    <style>
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--clay);
            text-decoration: none;
            font-size: .78rem;
            font-weight: 600;
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .back-btn svg {
            width: 15px;
            height: 15px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .info-field {
            margin-bottom: 14px;
        }

        .info-field label {
            display: block;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 5px;
        }

        .info-field-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            position: relative;
        }

        .info-field input,
        .info-field textarea {
            flex: 1;
            padding: .7rem 2.5rem .7rem .9rem;
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            color: var(--text);
            outline: none;
            transition: border-color .2s, background .2s;
        }

        /* Readonly state — looks like display */
        .info-field input[readonly],
        .info-field textarea[readonly] {
            background: var(--bg-white);
            border-color: var(--border);
            color: var(--text);
            cursor: default;
        }

        /* Active edit state */
        .info-field input:not([readonly]),
        .info-field textarea:not([readonly]) {
            border-color: var(--clay);
            background: #fffdf9;
        }

        .info-field select {
            width: 100%;
            padding: .7rem 2.5rem .7rem .9rem;
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            color: var(--text);
            outline: none;
            transition: border-color .2s;
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%23A0522D' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right .9rem center;
        }

        .info-field textarea {
            resize: vertical;
            min-height: 80px;
        }

        .info-field select:focus {
            border-color: var(--clay);
        }

        /* Rate field */
        .rate-display-wrap {
            display: flex;
            align-items: center;
            position: relative;
            flex: 1;
        }

        .rate-display {
            flex: 1;
            padding: .7rem 2.5rem .7rem .9rem;
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            color: var(--text);
            cursor: default;
            user-select: none;
        }

        .rate-input {
            display: none;
            flex: 1;
            padding: .7rem 2.5rem .7rem .9rem;
            background: #fffdf9;
            border: 1.5px solid var(--clay);
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            color: var(--text);
            outline: none;
        }

        .btn-field-edit {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            color: var(--clay);
            flex-shrink: 0;
            transition: background .15s;
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
        }

        .btn-field-edit:hover {
            background: var(--clay-pale);
        }

        .btn-field-edit svg {
            width: 15px;
            height: 15px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        .date-range-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
        }

        .date-range-wrap input[type="date"] {
            flex: 1;
            padding: .6rem .8rem;
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            color: var(--text);
            outline: none;
            transition: border-color .2s;
        }

        .date-range-wrap input[type="date"]:focus {
            border-color: var(--clay);
        }

        .date-range-sep {
            font-size: .8rem;
            color: var(--text-muted);
            flex-shrink: 0;
        }

        .btn-filter {
            padding: .6rem 1rem;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .75rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .18s;
            flex-shrink: 0;
        }

        .btn-filter:hover {
            background: var(--clay-dark);
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
        }

        .history-table thead tr {
            background: var(--clay);
            color: #fff;
        }

        .history-table th {
            padding: 10px 14px;
            text-align: left;
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .history-table th:first-child {
            border-radius: 10px 0 0 10px;
        }

        .history-table th:last-child {
            border-radius: 0 10px 10px 0;
            text-align: center;
        }

        .history-table td {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }

        .history-table td:last-child {
            text-align: center;
        }

        .history-table tr:last-child td {
            border-bottom: none;
        }

        .history-table tr:hover td {
            background: #faf8f6;
        }

        .btn-table-edit {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: var(--clay);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: background .15s;
            text-decoration: none;
        }

        .btn-table-edit:hover {
            background: var(--clay-pale);
        }

        .btn-table-edit svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        .pendapatan-box {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 20px;
        }

        .pendapatan-label {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 4px;
        }

        .pendapatan-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--clay);
        }

        .btn-delete-coach {
            width: 100%;
            padding: .85rem;
            background: var(--danger);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background .18s;
        }

        .btn-delete-coach:hover {
            background: #a93226;
        }

        .section-label-sm {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 10px;
        }

        .btn-save {
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
            margin-bottom: 8px;
            transition: background .18s;
        }

        .btn-save:hover {
            background: var(--clay-dark);
        }
    </style>
@endpush

@section('content')
    <div class="content">

        <a href="{{ route('admin.coaches') }}" class="back-btn">
            <svg viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Kembali
        </a>

        <form action="{{ route('admin.coaches.update', $coach->coach_id) }}" method="POST">
            @csrf @method('PUT')

            {{-- Nama Coach --}}
            <div class="info-field">
                <label>Nama Coach</label>
                <div class="info-field-wrap">
                    <input type="text" name="name" id="input-name" value="{{ $coach->name }}" required readonly>
                    <button type="button" class="btn-field-edit" onclick="toggleField('input-name', this)">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Nomor HP --}}
            <div class="info-field">
                <label>Nomor HP</label>
                <div class="info-field-wrap">
                    <input type="text" name="phone" id="input-phone" value="{{ $coach->phone_number }}"
                        placeholder="contoh: 08123456789" readonly>
                    <button type="button" class="btn-field-edit" onclick="toggleField('input-phone', this)">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Pendapatan Per Kelas --}}
            <div class="info-field">
                <label>Pendapatan Per Kelas</label>
                <div class="info-field-wrap" id="rate-wrap">
                    <div class="rate-display-wrap">
                        <span class="rate-display" id="rate-display">
                            Rp {{ number_format($coach->rate_per_class ?? 0, 0, ',', '.') }}
                        </span>
                        <input type="number" name="rate_per_class" id="rate-input" class="rate-input"
                            value="{{ $coach->rate_per_class ?? 0 }}" min="0" step="1000">
                        <button type="button" class="btn-field-edit" id="rate-edit-btn" onclick="toggleRateEdit()">
                            <svg viewBox="0 0 24 24">
                                <path d="M12 20h9" />
                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Kelas / Keahlian --}}
            <div class="info-field">
                <label>Kelas / Keahlian</label>
                <select name="class_id" required>
                    <option value="">-- Pilih Kelas --</option>
                    @foreach ($allClasses as $class)
                        <option value="{{ $class->class_id }}" {{ $coach->class_id == $class->class_id ? 'selected' : '' }}>
                            {{ $class->class_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Deskripsi --}}
            <div class="info-field">
                <label>Deskripsi</label>
                <div class="info-field-wrap" style="align-items:flex-start;">
                    <textarea name="bio" id="input-bio" readonly>{{ $coach->bio }}</textarea>
                    <button type="button" class="btn-field-edit" style="position:absolute;right:0;top:6px;transform:none;"
                        onclick="toggleField('input-bio', this)">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-save">Simpan Perubahan</button>
        </form>

        {{-- Riwayat Kelas --}}
        <div class="section-label-sm">Riwayat Kelas</div>

        <form method="GET" action="{{ route('admin.coaches.detail', $coach->coach_id) }}">
            <div class="date-range-wrap">
                <input type="date" name="from" value="{{ $from }}">
                <span class="date-range-sep">–</span>
                <input type="date" name="to" value="{{ $to }}">
                <button type="submit" class="btn-filter">Filter</button>
            </div>
        </form>

        <div class="section-card" style="overflow:hidden;">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Nama Kelas</th>
                        <th>Tanggal</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classHistory as $item)
                        <tr>
                            <td>{{ $item->title ?? $item->class_name }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->schedule_date)->format('d-m-Y') }}</td>
                            <td>
                                <a href="{{ route('admin.schedules.attendance', $item->schedule_id) }}"
                                    class="btn-table-edit">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--text-muted);padding:2rem;">
                                Tidak ada kelas pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Total Pendapatan --}}
        <div class="pendapatan-box">
            <div class="pendapatan-label">Total Pendapatan</div>
            <div class="pendapatan-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
        </div>

        {{-- Hapus Coach --}}
        <form action="{{ route('admin.coaches.destroy', $coach->coach_id) }}" method="POST"
            onsubmit="return confirm('Nonaktifkan coach {{ $coach->name }}?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-delete-coach">Hapus Pelatih</button>
        </form>

    </div>
@endsection

@push('scripts')
    <script>
        const ICON_EDIT =
            `<svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>`;
        const ICON_DONE = `<svg viewBox="0 0 24 24" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>`;

        function toggleField(inputId, btn) {
            const el = document.getElementById(inputId);
            const isReadonly = el.hasAttribute('readonly');

            if (isReadonly) {
                el.removeAttribute('readonly');
                el.focus();
                // Move cursor to end
                const len = el.value.length;
                el.setSelectionRange(len, len);
                btn.innerHTML = ICON_DONE;
            } else {
                el.setAttribute('readonly', true);
                btn.innerHTML = ICON_EDIT;
            }
        }

        // Close edit on Enter for text inputs (not textarea)
        document.querySelectorAll('.info-field input[type="text"]').forEach(input => {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.setAttribute('readonly', true);
                    // Reset sibling button icon
                    const btn = this.closest('.info-field-wrap').querySelector('.btn-field-edit');
                    if (btn) btn.innerHTML = ICON_EDIT;
                }
            });
        });

        // Rate field toggle (separate because it uses display/input swap)
        function toggleRateEdit() {
            const display = document.getElementById('rate-display');
            const input = document.getElementById('rate-input');
            const btn = document.getElementById('rate-edit-btn');

            const isEditing = input.style.display === 'block';

            if (isEditing) {
                const raw = parseInt(input.value) || 0;
                display.textContent = 'Rp ' + raw.toLocaleString('id-ID');
                display.style.display = '';
                input.style.display = 'none';
                btn.innerHTML = ICON_EDIT;
            } else {
                display.style.display = 'none';
                input.style.display = 'block';
                input.focus();
                input.select();
                btn.innerHTML = ICON_DONE;
            }
        }

        document.getElementById('rate-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                toggleRateEdit();
            }
        });
    </script>
@endpush
