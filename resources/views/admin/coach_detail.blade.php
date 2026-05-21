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
        }

        .info-field input,
        .info-field select,
        .info-field textarea {
            flex: 1;
            padding: .7rem .9rem;
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            color: var(--text);
            outline: none;
            transition: border-color .2s;
        }

        .info-field textarea {
            resize: vertical;
            min-height: 80px;
        }

        .info-field input:focus,
        .info-field select:focus,
        .info-field textarea:focus {
            border-color: var(--clay);
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

        .pendapatan-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .pendapatan-label {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .pendapatan-value {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--clay);
        }

        .btn-add-pendapatan {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 14px;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .73rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .18s;
        }

        .btn-add-pendapatan:hover {
            background: var(--clay-dark);
        }

        .btn-add-pendapatan svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
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
            max-width: 380px;
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

        .modal-field input {
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

        .modal-field input:focus {
            border-color: var(--clay);
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

        <a href="{{ route('admin.coaches') }}" class="back-btn">
            <svg viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Kembali
        </a>

        <form action="{{ route('admin.coaches.update', $coach->coach_id) }}" method="POST">
            @csrf @method('PUT')

            <div class="info-field">
                <label>Nama Coach</label>
                <div class="info-field-wrap">
                    <input type="text" name="name" value="{{ $coach->name }}" required>
                    <button type="button" class="btn-field-edit">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="info-field">
                <label>Nomor HP</label>
                <div class="info-field-wrap">
                    <input type="text" name="phone" value="{{ $coach->phone_number }}"
                        placeholder="contoh: 08123456789">
                    <button type="button" class="btn-field-edit">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="info-field">
                <label>Kelas / Keahlian</label>
                <div class="info-field-wrap">
                    <select name="class_id" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach ($allClasses as $class)
                            <option value="{{ $class->class_id }}"
                                {{ $coach->class_id == $class->class_id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="button" class="btn-field-edit">
                        <svg viewBox="0 0 24 24">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="info-field">
                <label>Deskripsi</label>
                <div class="info-field-wrap">
                    <textarea name="bio">{{ $coach->bio }}</textarea>
                    <button type="button" class="btn-field-edit" style="align-self:flex-start;margin-top:6px;">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-primary" style="margin-bottom:8px;">Simpan Perubahan</button>
        </form>

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
                                    class="btn-table-edit"> <svg viewBox="0 0 24 24">
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

        <div class="pendapatan-box">
            <div class="pendapatan-label">Total Pendapatan</div>
            <div class="pendapatan-value">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </div>
        </div>

        <form action="{{ route('admin.coaches.destroy', $coach->coach_id) }}" method="POST"
            onsubmit="return confirm('Nonaktifkan coach {{ $coach->name }}?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-delete-coach">Hapus Pelatih</button>
        </form>

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
    </script>
@endpush
