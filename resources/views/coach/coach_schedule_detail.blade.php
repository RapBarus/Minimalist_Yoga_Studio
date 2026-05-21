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

        .upload-area.has-file {
            border-color: var(--clay);
            background: rgba(160, 82, 45, .05);
        }

        .upload-area.has-file svg {
            stroke: var(--clay);
        }

        .upload-filename {
            font-size: .8rem;
            font-weight: 600;
            color: var(--clay);
            margin-top: 4px;
            word-break: break-all;
        }

        .upload-filesize {
            font-size: .72rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .upload-change-hint {
            font-size: .7rem;
            color: var(--text-muted);
            margin-top: 6px;
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
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tidakHadir as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>
                                <span class="status-badge-tidak">Tidak Hadir</span>
                                <input type="hidden" name="attendance[{{ $p->booking_id }}]" id="att-{{ $p->booking_id }}"
                                    value="tidak_hadir">
                            </td>
                            <td>
                                <button type="button" class="btn-check" onclick="markHadir({{ $p->booking_id }}, this)"
                                    title="Tandai Hadir">
                                    <svg class="check-hadir" viewBox="0 0 24 24">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--text-muted);">—</td>
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
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hadir as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>
                                <span class="status-badge-hadir">Hadir</span>
                                <input type="hidden" name="attendance[{{ $p->booking_id }}]" id="att-{{ $p->booking_id }}"
                                    value="hadir">
                            </td>
                            <td>
                                <button type="button" class="btn-check"
                                    onclick="markTidakHadir({{ $p->booking_id }}, this)" title="Tandai Tidak Hadir">
                                    <svg class="check-tidak" viewBox="0 0 24 24">
                                        <line x1="18" y1="6" x2="6" y2="18" />
                                        <line x1="6" y1="6" x2="18" y2="18" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--text-muted);">—</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Upload Bukti Hadir --}}
        <div class="upload-section">
            <span class="upload-label">Upload Bukti Hadir</span>
            <div class="upload-area" id="uploadArea">
                <input type="file" name="bukti_hadir" accept="image/*" id="buktiHadirInput">

                @if ($existingPhoto)
                    <img src="{{ $existingPhoto }}"
                        style="width:100%;border-radius:10px;max-height:180px;object-fit:cover;margin-bottom:10px;">
                    <div style="font-size:.72rem;color:var(--clay);font-weight:600;">
                        Foto sudah diupload — tap untuk ganti
                    </div>
                @else
                    <svg id="uploadIcon" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <polyline points="21 15 16 10 5 21" />
                    </svg>
                    <div class="upload-area-text" id="uploadText">Select file</div>
                    <div class="upload-filename" id="uploadFilename" style="display:none;"></div>
                    <div class="upload-filesize" id="uploadFilesize" style="display:none;"></div>
                    <div class="upload-change-hint" id="uploadHint" style="display:none;">Tap to change file</div>
                @endif
            </div>
        </div>

        <button type="submit" class="btn-update">Update Kelas</button>

    </form>

@endsection

@push('scripts')
    <script>
        function markHadir(bookingId, btn) {
            document.getElementById('att-' + bookingId).value = 'hadir';
            const tr = btn.closest('tr');
            const badge = tr.querySelector('.status-badge-tidak, .status-badge-hadir');
            if (badge) {
                badge.className = 'status-badge-hadir';
                badge.textContent = 'Hadir';
            }
            const svg = btn.querySelector('svg');
            svg.className.baseVal = 'check-tidak';
            svg.innerHTML = '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>';
            btn.setAttribute('onclick', 'markTidakHadir(' + bookingId + ', this)');
        }

        function markTidakHadir(bookingId, btn) {
            document.getElementById('att-' + bookingId).value = 'tidak_hadir';
            const tr = btn.closest('tr');
            const badge = tr.querySelector('.status-badge-tidak, .status-badge-hadir');
            if (badge) {
                badge.className = 'status-badge-tidak';
                badge.textContent = 'Tidak Hadir';
            }
            const svg = btn.querySelector('svg');
            svg.className.baseVal = 'check-hadir';
            svg.innerHTML = '<polyline points="20 6 9 17 4 12"/>';
            btn.setAttribute('onclick', 'markHadir(' + bookingId + ', this)');
        }

        document.getElementById('buktiHadirInput').addEventListener('change', function() {
            const area = document.getElementById('uploadArea');
            const icon = document.getElementById('uploadIcon');
            const text = document.getElementById('uploadText');
            const filename = document.getElementById('uploadFilename');
            const filesize = document.getElementById('uploadFilesize');
            const hint = document.getElementById('uploadHint');

            if (this.files && this.files[0]) {
                const file = this.files[0];
                const sizeKB = (file.size / 1024).toFixed(1);
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);

                area.classList.add('has-file');
                text.style.display = 'none';

                // Swap icon to checkmark
                icon.innerHTML = '<polyline points="20 6 9 17 4 12"/>';

                filename.textContent = file.name;
                filename.style.display = 'block';

                filesize.textContent = file.size > 1024 * 1024 ?
                    sizeMB + ' MB' :
                    sizeKB + ' KB';
                filesize.style.display = 'block';

                hint.style.display = 'block';
            } else {
                area.classList.remove('has-file');
                text.style.display = 'block';
                icon.innerHTML =
                    '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>';
                filename.style.display = 'none';
                filesize.style.display = 'none';
                hint.style.display = 'none';
            }
        });
    </script>
@endpush
