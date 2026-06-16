@extends('layouts.admin')

@section('title', 'View Jadwal | Minimalist Studio')
@section('page-title', 'View Jadwal')
@section('page-sub', $schedule->class_name . ' — ' . \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y'))

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
            margin-bottom: 12px;
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

        .info-field input,
        .info-field select {
            width: 100%;
            padding: .7rem .9rem;
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            color: var(--text);
            outline: none;
            transition: border-color .2s;
            appearance: none;
        }

        .info-field input:focus,
        .info-field select:focus {
            border-color: var(--clay);
        }

        .info-field-row {
            display: flex;
            gap: 10px;
        }

        .info-field-row .info-field {
            flex: 1;
        }

        .select-wrap {
            position: relative;
        }

        .select-wrap select {
            padding-right: 2rem;
        }

        .select-wrap svg {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 14px;
            height: 14px;
            stroke: var(--clay);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            pointer-events: none;
        }

        .peserta-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 8px;
            margin-bottom: 14px;
        }

        .peserta-title {
            font-weight: 700;
            font-size: .95rem;
            color: var(--text);
        }

        .btn-input-peserta {
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

        .btn-input-peserta:hover {
            background: var(--clay-dark);
        }

        .btn-input-peserta svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
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
            transition: background .18s;
            margin-top: 4px;
        }

        .btn-save:hover {
            background: var(--clay-dark);
        }

        .peserta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
        }

        .peserta-table thead tr {
            background: var(--clay);
            color: #fff;
        }

        .peserta-table th {
            padding: 10px 14px;
            text-align: left;
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .peserta-table th:first-child {
            border-radius: 10px 0 0 10px;
        }

        .peserta-table th:last-child {
            border-radius: 0 10px 10px 0;
        }

        .peserta-table td {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }

        .peserta-table tr:last-child td {
            border-bottom: none;
        }

        .peserta-table tr:hover td {
            background: #faf8f6;
        }

        .status-valid {
            display: inline-block;
            padding: 3px 10px;
            background: rgba(39, 174, 96, .12);
            color: #27AE60;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .06em;
        }

        .status-pending {
            display: inline-block;
            padding: 3px 10px;
            background: rgba(243, 156, 18, .12);
            color: #F39C12;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .06em;
        }

        .status-cancelled {
            display: inline-block;
            padding: 3px 10px;
            background: rgba(192, 57, 43, .1);
            color: #C0392B;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .06em;
        }

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

        <a href="{{ route('admin.dashboard') }}" class="back-btn">
            <svg viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Kembali
        </a>

        <form action="{{ route('admin.schedules.update', $schedule->schedule_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="info-field">
                <label>Nama Kelas (Custom)</label>
                <input type="text" name="custom_name" value="{{ $schedule->title ?? '' }}"
                    placeholder="{{ $schedule->class_name }}">
            </div>

            <div class="info-field">
                <label>Tanggal</label>
                <input type="date" name="schedule_date"
                    value="{{ \Carbon\Carbon::parse($schedule->schedule_date)->format('Y-m-d') }}" required>
            </div>

            <div class="info-field">
                <label>Kelas</label>
                <div class="select-wrap">
                    <select name="class_id" required>
                        @foreach ($classes as $class)
                            <option value="{{ $class->class_id }}"
                                {{ $schedule->class_id == $class->class_id ? 'selected' : '' }}>
                                {{ $class->class_name }}
                            </option>
                        @endforeach
                    </select>
                    <svg viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
            </div>

            <div class="info-field">
                <label>Coach</label>
                <div class="select-wrap">
                    <select name="coach_id" required>
                        @foreach ($coaches as $coach)
                            <option value="{{ $coach->coach_id }}"
                                {{ $schedule->coach_id == $coach->coach_id ? 'selected' : '' }}>
                                {{ $coach->name }} — {{ $coach->class_name }}
                            </option>
                        @endforeach
                    </select>
                    <svg viewBox="0 0 24 24">
                        <polyline points="6 9 12 15 18 9" />
                    </svg>
                </div>
            </div>

            <div class="info-field">
                <label>Jam Kelas</label>
                <div class="info-field-row">
                    <div class="info-field" style="margin-bottom:0;">
                        <input type="time" name="start_time"
                            value="{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}" required>
                    </div>
                    <div class="info-field" style="margin-bottom:0;">
                        <input type="time" name="end_time"
                            value="{{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}" required>
                    </div>
                </div>
            </div>

            <div class="info-field">
                <label>Harga</label>
                <input type="number" name="rate_per_class" value="{{ $schedule->rate_per_class ?? 0 }}" min="0"
                    step="1000" required>
            </div>

            <div class="info-field">
                <label>Kuota Kelas</label>
                <input type="number" name="capacity" value="{{ $schedule->capacity }}" min="1" max="100"
                    required>
            </div>

            <button type="submit" class="btn-save">Simpan Perubahan</button>
        </form>

        {{-- Peserta --}}
        <div class="peserta-header">
            <div class="peserta-title">Peserta Kelas</div>
            <button class="btn-input-peserta" onclick="openModal('modal-peserta')">
                <svg viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Input peserta
            </button>
        </div>

        <div class="section-card" style="overflow:hidden;">
            <table class="peserta-table">
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Metode Pembayaran</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ strtoupper($p->payment_channel ?? ($p->payment_type ?? '—')) }}</td>
                            <td>
                                @if ($p->booking_status === 'cancelled')
                                    <span class="status-cancelled">DIBATALKAN</span>
                                @elseif (in_array($p->transaction_status, ['settlement', 'settled', 'capture', 'cash_paid']) ||
                                        in_array($p->booking_status, ['confirmed', 'attended']))
                                    <span class="status-valid">VALID</span>
                                @else
                                    <span class="status-pending">PENDING</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--text-muted);padding:2rem;">
                                Belum ada peserta.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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

        document.querySelectorAll('.modal-overlay').forEach(o => o.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('open');
                document.body.style.overflow = '';
            }
        }));
    </script>
@endpush

<div class="modal-overlay" id="modal-peserta">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Tambah Peserta</div>
            <button class="modal-close" onclick="closeModal('modal-peserta')">
                <svg viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.schedules.peserta', $schedule->schedule_id) }}" method="POST"
            class="modal-form">
            @csrf
            <div class="modal-field">
                <label>Nama Peserta</label>
                <input type="text" name="name" placeholder="Masukan Nama Peserta" required>
            </div>
            <div class="modal-field">
                <label>Metode Pembayaran</label>
                <select name="payment_type" required>
                    <option value="">Pilih Metode Pembayaran</option>
                    <option value="cash">Cash</option>
                    <option value="qris">QRIS</option>
                    <option value="transfer">Transfer Bank</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Harga</label>
                <input type="number" name="amount" placeholder="Masukan Harga"
                    value="{{ $schedule->rate_per_class ?? 0 }}" min="0" step="1000" required>
            </div>
            <button type="submit" class="btn-modal-submit">Tambah Peserta</button>
        </form>
    </div>
</div>
