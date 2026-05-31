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

        .absen-table th:last-child {
            text-align: center;
        }

        .absen-table td {
            padding: 10px 14px;
            font-size: .82rem;
            background: #fff;
            border-bottom: 1px solid var(--border);
            color: var(--text);
            vertical-align: middle;
        }

        .absen-table td:last-child {
            text-align: center;
        }

        .absen-table tr:last-child td {
            border-bottom: none;
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

        /* Toggle switch */
        .toggle-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-input {
            display: none;
        }

        .toggle-label {
            position: relative;
            width: 42px;
            height: 24px;
            border-radius: 12px;
            background: var(--danger, #C0392B);
            cursor: pointer;
            transition: background .2s;
            display: block;
            flex-shrink: 0;
        }

        .toggle-input:checked+.toggle-label {
            background: #27AE60;
        }

        .toggle-label::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            transition: transform .2s;
            box-shadow: 0 1px 4px rgba(0, 0, 0, .2);
        }

        .toggle-input:checked+.toggle-label::after {
            transform: translateX(18px);
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

        /* Existing photo box */
        .photo-box {
            border: 2px dashed var(--clay);
            border-radius: 14px;
            padding: 16px;
            background: rgba(160, 82, 45, .04);
            cursor: pointer;
        }

        .photo-box img {
            width: 100%;
            border-radius: 10px;
            max-height: 180px;
            object-fit: cover;
            display: block;
        }

        .photo-box-caption {
            font-size: .72rem;
            color: var(--clay);
            font-weight: 600;
            margin-top: 8px;
            text-align: center;
        }

        .btn-update {
            display: block;
            width: calc(100% - 40px);
            margin: 20px 20px 12px;
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

        .btn-hapus-foto {
            display: block;
            width: calc(100% - 40px);
            margin: 0 20px 40px;
            padding: .75rem;
            background: rgba(192, 57, 43, .08);
            border: 1.5px solid rgba(192, 57, 43, .25);
            color: var(--danger);
            font-family: 'Raleway', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            letter-spacing: .08em;
            text-transform: uppercase;
            transition: background .15s;
        }

        .btn-hapus-foto:hover {
            background: rgba(192, 57, 43, .16);
        }

        /* ── Custom confirm modal ── */
        .confirm-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .45);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .confirm-overlay.open {
            display: flex;
        }

        .confirm-box {
            background: #fff;
            border-radius: 18px;
            padding: 28px 24px 20px;
            max-width: 320px;
            width: 100%;
            box-shadow: 0 8px 40px rgba(0, 0, 0, .18);
            animation: modalIn .22s ease both;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(.94) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .confirm-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(192, 57, 43, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
        }

        .confirm-icon svg {
            width: 24px;
            height: 24px;
            stroke: var(--danger);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .confirm-title {
            font-size: .95rem;
            font-weight: 700;
            color: var(--text);
            text-align: center;
            margin-bottom: 6px;
        }

        .confirm-desc {
            font-size: .78rem;
            color: var(--text-muted);
            text-align: center;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .confirm-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .btn-confirm-cancel {
            padding: .7rem;
            background: var(--bg, #F2EFEB);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            color: var(--text-muted);
            cursor: pointer;
            transition: background .15s;
        }

        .btn-confirm-cancel:hover {
            background: #e8e4df;
        }

        .btn-confirm-ok {
            padding: .7rem;
            background: var(--danger);
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .78rem;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-confirm-ok:hover {
            background: #a93226;
        }
    </style>
@endpush

@section('content')

    {{-- Custom header --}}
    <div class="detail-header">
        <a href="{{ route('coach.dashboard') }}" class="detail-back">
            <svg viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6" />
            </svg>
        </a>
        <div class="detail-title">{{ $schedule->class_name }}</div>
    </div>

    {{-- ── MAIN UPDATE FORM ── --}}
    <form action="{{ route('coach.schedule.update', $schedule->schedule_id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Absensi Kelas --}}
        <div class="absen-section">
            <div class="absen-section-title">Absensi Kelas</div>
            <table class="absen-table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($participants as $p)
                        @php $isHadir = $p->status === 'attended'; @endphp
                        <tr id="row-{{ $p->booking_id }}">
                            <td>{{ $p->name }}</td>
                            <td>
                                <span id="badge-{{ $p->booking_id }}"
                                    class="{{ $isHadir ? 'status-badge-hadir' : 'status-badge-tidak' }}">
                                    {{ $isHadir ? 'Hadir' : 'Tidak Hadir' }}
                                </span>
                                <input type="hidden" name="attendance[{{ $p->booking_id }}]" id="att-{{ $p->booking_id }}"
                                    value="{{ $isHadir ? 'hadir' : 'tidak_hadir' }}">
                            </td>
                            <td>
                                <div class="toggle-wrap">
                                    <input type="checkbox" class="toggle-input" id="toggle-{{ $p->booking_id }}"
                                        {{ $isHadir ? 'checked' : '' }}
                                        onchange="toggleAttendance({{ $p->booking_id }}, this.checked)">
                                    <label class="toggle-label" for="toggle-{{ $p->booking_id }}"></label>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--text-muted);padding:1.5rem;">
                                Belum ada peserta terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Upload Bukti Hadir --}}
        <div class="upload-section">
            <span class="upload-label">Upload Bukti Hadir</span>

            @if ($existingPhoto)
                <div class="photo-box" onclick="document.getElementById('replaceInput').click()">
                    <img src="{{ $existingPhoto }}" alt="Bukti Hadir">
                    <div class="photo-box-caption" id="replaceCaption">
                        Foto sudah diupload — tap untuk ganti
                    </div>
                </div>
                <input type="file" name="bukti_hadir" accept="image/*" id="replaceInput" style="display:none;">
            @else
                <div class="upload-area" id="uploadArea">
                    <input type="file" name="bukti_hadir" accept="image/*" id="buktiHadirInput">
                    <svg id="uploadIcon" viewBox="0 0 24 24">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <circle cx="8.5" cy="8.5" r="1.5" />
                        <polyline points="21 15 16 10 5 21" />
                    </svg>
                    <div class="upload-area-text" id="uploadText">Select file</div>
                    <div class="upload-filename" id="uploadFilename" style="display:none;"></div>
                    <div class="upload-filesize" id="uploadFilesize" style="display:none;"></div>
                    <div class="upload-change-hint" id="uploadHint" style="display:none;">Tap to change file</div>
                </div>
            @endif
        </div>

        <button type="submit" class="btn-update">Update Kelas</button>

    </form>
    {{-- ── END MAIN FORM ── --}}

    {{-- Hapus Foto button — triggers custom modal --}}
    @if ($existingPhoto)
        <button type="button" class="btn-hapus-foto" onclick="openDeleteModal()">
            Hapus Foto
        </button>
    @endif

    {{-- Custom confirm modal --}}
    <div class="confirm-overlay" id="deleteModal">
        <div class="confirm-box">
            <div class="confirm-icon">
                <svg viewBox="0 0 24 24">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                    <path d="M10 11v6M14 11v6" />
                    <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                </svg>
            </div>
            <div class="confirm-title">Hapus Foto?</div>
            <div class="confirm-desc">
                Foto bukti hadir akan dihapus dan status jadwal akan kembali ke <strong>upcoming</strong>.
            </div>
            <div class="confirm-actions">
                <button type="button" class="btn-confirm-cancel" onclick="closeDeleteModal()">
                    Batal
                </button>
                <button type="button" class="btn-confirm-ok" onclick="submitDelete()">
                    Hapus
                </button>
            </div>
        </div>
    </div>

    {{-- Hidden delete form --}}
    @if ($existingPhoto)
        <form id="deletePhotoForm" action="{{ route('coach.schedule.photo.delete', $schedule->schedule_id) }}"
            method="POST" style="display:none;">
            @csrf @method('DELETE')
        </form>
    @endif

@endsection

@push('scripts')
    <script>
        function toggleAttendance(bookingId, isChecked) {
            const hidden = document.getElementById('att-' + bookingId);
            const badge = document.getElementById('badge-' + bookingId);
            if (isChecked) {
                hidden.value = 'hadir';
                badge.className = 'status-badge-hadir';
                badge.textContent = 'Hadir';
            } else {
                hidden.value = 'tidak_hadir';
                badge.className = 'status-badge-tidak';
                badge.textContent = 'Tidak Hadir';
            }
        }

        function openDeleteModal() {
            document.getElementById('deleteModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        function submitDelete() {
            document.getElementById('deletePhotoForm').submit();
        }

        // Close modal on overlay click
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });

        // Replace input caption update
        const replaceInput = document.getElementById('replaceInput');
        if (replaceInput) {
            replaceInput.addEventListener('change', function() {
                const caption = document.getElementById('replaceCaption');
                if (caption && this.files && this.files[0]) {
                    caption.textContent = this.files[0].name + ' dipilih — klik Update Kelas untuk menyimpan';
                }
            });
        }

        // Normal upload area
        const buktiInput = document.getElementById('buktiHadirInput');
        if (buktiInput) {
            buktiInput.addEventListener('change', function() {
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
                    if (text) text.style.display = 'none';
                    if (icon) icon.innerHTML = '<polyline points="20 6 9 17 4 12"/>';
                    if (filename) {
                        filename.textContent = file.name;
                        filename.style.display = 'block';
                    }
                    if (filesize) {
                        filesize.textContent = file.size > 1024 * 1024 ? sizeMB + ' MB' : sizeKB + ' KB';
                        filesize.style.display = 'block';
                    }
                    if (hint) hint.style.display = 'block';
                } else {
                    area.classList.remove('has-file');
                    if (text) text.style.display = 'block';
                    if (icon) icon.innerHTML =
                        '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>';
                    if (filename) filename.style.display = 'none';
                    if (filesize) filesize.style.display = 'none';
                    if (hint) hint.style.display = 'none';
                }
            });
        }
    </script>
@endpush
