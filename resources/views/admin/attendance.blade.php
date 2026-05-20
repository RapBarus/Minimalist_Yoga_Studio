@extends('layouts.admin')

@section('title', 'Absensi | Minimalist Studio')
@section('page-title', 'Absensi')
@section('page-sub', ($schedule->title ?? $schedule->class_name) . ' — ' .
    \Carbon\Carbon::parse($schedule->schedule_date)->format('d M Y'))

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

            .section-label-sm {
                font-size: .68rem;
                font-weight: 700;
                letter-spacing: .12em;
                text-transform: uppercase;
                color: var(--text-muted);
                margin-bottom: 10px;
                margin-top: 8px;
            }

            .attendance-table {
                width: 100%;
                border-collapse: collapse;
                font-size: .82rem;
            }

            .attendance-table thead tr {
                background: var(--clay);
                color: #fff;
            }

            .attendance-table th {
                padding: 10px 14px;
                text-align: left;
                font-size: .7rem;
                font-weight: 600;
                letter-spacing: .08em;
                text-transform: uppercase;
            }

            .attendance-table th:first-child {
                border-radius: 10px 0 0 10px;
            }

            .attendance-table th:last-child {
                border-radius: 0 10px 10px 0;
            }

            .attendance-table td {
                padding: 10px 14px;
                border-bottom: 1px solid var(--border);
                color: var(--text);
            }

            .attendance-table tr:last-child td {
                border-bottom: none;
            }

            .attendance-table tr:hover td {
                background: #faf8f6;
            }

            .upload-wrap {
                background: var(--bg-white);
                border: 1.5px solid var(--border);
                border-radius: 14px;
                padding: 20px;
            }

            .upload-label {
                font-size: .7rem;
                font-weight: 700;
                letter-spacing: .1em;
                text-transform: uppercase;
                color: var(--text-muted);
                margin-bottom: 10px;
                display: block;
            }

            .upload-input-wrap {
                display: flex;
                align-items: center;
                gap: 10px;
                border: 1.5px solid var(--clay);
                border-radius: 10px;
                padding: .6rem .9rem;
                background: var(--bg-white);
                cursor: pointer;
            }

            .upload-input-wrap input[type="file"] {
                display: none;
            }

            .upload-filename {
                flex: 1;
                font-size: .82rem;
                color: var(--text-muted);
                font-family: 'Raleway', sans-serif;
            }

            .upload-input-wrap svg {
                width: 18px;
                height: 18px;
                stroke: var(--clay);
                fill: none;
                stroke-width: 1.8;
                stroke-linecap: round;
                flex-shrink: 0;
            }

            .btn-upload-submit {
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
                margin-top: 12px;
                transition: background .18s;
            }

            .btn-upload-submit:hover {
                background: var(--clay-dark);
            }

            .uploaded-img {
                width: 100%;
                border-radius: 10px;
                margin-top: 10px;
                max-height: 200px;
                object-fit: cover;
            }
        </style>
    @endpush

@section('content')
    <div class="content">

        <a href="{{ route('admin.coaches.detail', $schedule->coach_id) }}" class="back-btn">
            <svg viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Kembali
        </a>

        {{-- Schedule title --}}
        <div
            style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:700;color:var(--clay);text-transform:uppercase;">
            {{ $schedule->title ?? $schedule->class_name }}
        </div>

        {{-- Tidak Hadir --}}
        <div class="section-label-sm">Tidak Hadir</div>
        <div class="section-card" style="overflow:hidden;">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absent as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>Tidak Hadir</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align:center;color:var(--text-muted);padding:1.5rem;">Semua
                                hadir.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Hadir --}}
        <div class="section-label-sm">Hadir</div>
        <div class="section-card" style="overflow:hidden;">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($present as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>Hadir</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align:center;color:var(--text-muted);padding:1.5rem;">Belum ada
                                data kehadiran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Upload Bukti Hadir --}}
        <div class="upload-wrap">
            <span class="upload-label">Upload Bukti Hadir</span>

            @if ($attendance && $attendance->photo_url)
                <img src="{{ $attendance->photo_url }}" class="uploaded-img" alt="Bukti Hadir">
            @endif

            <form action="{{ route('admin.schedules.upload-attendance', $schedule->schedule_id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="upload-input-wrap" onclick="document.getElementById('file-input').click()">
                    <svg viewBox="0 0 24 24">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                    <span class="upload-filename" id="file-name">
                        {{ $attendance && $attendance->photo_url ? basename($attendance->photo_url) : 'Pilih file gambar...' }}
                    </span>
                    <input type="file" id="file-input" name="photo" accept="image/*"
                        onchange="document.getElementById('file-name').textContent = this.files[0]?.name ?? 'Pilih file gambar...'">
                </div>
                <button type="submit" class="btn-upload-submit">Simpan Bukti</button>
            </form>
        </div>

    </div>
@endsection
