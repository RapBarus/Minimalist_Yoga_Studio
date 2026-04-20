@extends('layouts.coach')

@section('title', 'Detail Jadwal | Minimalist Studio')

@push('styles')
    <style>
        .detail-header {
            background: var(--clay);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .detail-back {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .detail-back svg {
            width: 20px;
            height: 20px;
            stroke: #fff;
            fill: none;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .detail-title {
            font-weight: 700;
            font-size: 1rem;
            color: #fff;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        /* Section */
        .absen-section {
            padding: 0 20px;
            margin-top: 20px;
        }

        .absen-section-title {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 10px;
        }

        .absen-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .06);
        }

        .absen-table thead tr {
            background: var(--clay);
            color: #fff;
        }

        .absen-table th {
            padding: 10px 14px;
            text-align: left;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .06em;
        }

        .absen-table td {
            padding: 10px 14px;
            font-size: .82rem;
            background: #fff;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }

        .absen-table tr:last-child td {
            border-bottom: none;
        }

        .absen-table td:last-child {
            text-align: right;
        }

        .btn-check {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-check svg {
            width: 20px;
            height: 20px;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            fill: none;
        }

        .check-hadir {
            stroke: var(--clay);
        }

        .check-tidak {
            stroke: var(--danger);
        }

        /* Upload area */
        .upload-section {
            padding: 0 20px;
            margin-top: 20px;
        }

        .upload-label {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 10px;
            display: block;
        }

        .upload-area {
            border: 2px dashed var(--border);
            border-radius: 14px;
            padding: 32px 20px;
            text-align: center;
            cursor: pointer;
            transition: border-color .18s;
            background: #fff;
            position: relative;
        }

        .upload-area:hover {
            border-color: var(--clay);
        }

        .upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .upload-area svg {
            width: 32px;
            height: 32px;
            stroke: var(--text-muted);
            fill: none;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            margin-bottom: 8px;
        }

        .upload-area-text {
            font-size: .8rem;
            color: var(--text-muted);
        }

        .btn-update {
            display: block;
            width: calc(100% - 40px);
            margin: 20px 20px 0;
            padding: .85rem;
            background: var(--clay);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background .18s;
        }

        .btn-update:hover {
            background: var(--clay-dark);
        }

        .status-badge-hadir {
            display: inline-block;
            padding: 2px 10px;
            background: rgba(39, 174, 96, .12);
            color: #27AE60;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 600;
        }

        .status-badge-tidak {
            display: inline-block;
            padding: 2px 10px;
            background: rgba(192, 57, 43, .1);
            color: var(--danger);
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')

    {{-- Custom header for this page --}}
    <div class="detail-header">
        <a href="{{ route('coach.dashboard') }}" class="detail-back">
            <svg viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6" />
            </svg>
        </a>
        <div class="detail-title">{{ $schedule->class_name }}</div>
    </div>

    <form action="{{ route('coach.schedule.update', $schedule->schedule_id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Tidak Hadir --}}
        <div class="absen-section">
            <div class="absen-section-title">Tidak Hadir</div>
            <table class="absen-table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tidakHadir as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>
                                <span class="status-badge-tidak">Tidak Hadir</span>
                                <button type="button" class="btn-check" onclick="markHadir({{ $p->booking_id }}, this)"
                                    title="Tandai Hadir">
                                    <svg class="check-hadir" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </button>
                                <input type="hidden" name="attendance[{{ $p->booking_id }}]" id="att-{{ $p->booking_id }}"
                                    value="tidak_hadir">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align:center;color:var(--text-muted);">—</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Hadir --}}
        <div class="absen-section" style="margin-top:16px;">
            <div class="absen-section-title">Hadir</div>
            <table class="absen-table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hadir as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>
                                <span class="status-badge-hadir">Hadir</span>
                                <button type="button" class="btn-check"
                                    onclick="markTidakHadir({{ $p->booking_id }}, this)" title="Tandai Tidak Hadir">
                                    <svg class="check-tidak" viewBox="0 0 24 24">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                </button>
                                <input type="hidden" name="attendance[{{ $p->booking_id }}]" id="att-{{ $p->booking_id }}"
                                    value="hadir">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align:center;color:var(--text-muted);">—</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Upload Bukti Hadir --}}
        <div class="upload-section">
            <span class="upload-label">Upload Bukti Hadir</span>
            <div class="upload-area">
                <input type="file" name="bukti_hadir" accept="image/*">
                <svg viewBox="0 0 24 24">
                    <rect x="3" y="3" width="18" height="18" rx="2" />
                    <circle cx="8.5" cy="8.5" r="1.5" />
                    <polyline points="21 15 16 10 5 21" />
                </svg>
                <div class="upload-area-text">Select file</div>
            </div>
        </div>

        <button type="submit" class="btn-update">Update Kelas</button>

    </form>

@endsection

@push('scripts')
    <script>
        function markHadir(bookingId, btn) {
            document.getElementById('att-' + bookingId).value = 'hadir';
            const td = btn.closest('td');
            td.querySelector('.status-badge-tidak').outerHTML = '<span class="status-badge-hadir">Hadir</span>';
            btn.querySelector('svg').classList.remove('check-hadir');
            btn.querySelector('svg').innerHTML =
                '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>';
            btn.querySelector('svg').classList.add('check-tidak');
            btn.setAttribute('onclick', 'markTidakHadir(' + bookingId + ', this)');
        }

        function markTidakHadir(bookingId, btn) {
            document.getElementById('att-' + bookingId).value = 'tidak_hadir';
            const td = btn.closest('td');
            td.querySelector('.status-badge-hadir').outerHTML = '<span class="status-badge-tidak">Tidak Hadir</span>';
            btn.querySelector('svg').classList.remove('check-tidak');
            btn.querySelector('svg').innerHTML = '<polyline points="20 6 9 17 4 12"/>';
            btn.querySelector('svg').classList.add('check-hadir');
            btn.setAttribute('onclick', 'markHadir(' + bookingId + ', this)');
        }
    </script>
@endpush
