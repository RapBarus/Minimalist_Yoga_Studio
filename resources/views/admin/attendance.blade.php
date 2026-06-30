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
                text-align: center;
                font-size: .7rem;
                font-weight: 600;
                letter-spacing: .08em;
                text-transform: uppercase;
            }

            .attendance-table th:first-child {
                border-radius: 10px 0 0 10px;
                text-align: left;
            }

            .attendance-table th:last-child {
                border-radius: 0 10px 10px 0;
            }

            .attendance-table td {
                padding: 10px 14px;
                border-bottom: 1px solid var(--border);
                color: var(--text);
                text-align: center;
            }

            .attendance-table td:first-child {
                text-align: left;
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

        {{-- Combined Absensi Table --}}
        <div class="section-label-sm">Absensi Kelas</div>
        <div class="section-card" style="overflow:hidden;">
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php $all = $present->merge($absent)->sortBy('name'); @endphp
                    @forelse($all as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>
                                @if ($p->status === 'attended')
                                    <span
                                        style="display:inline-block;padding:3px 10px;background:rgba(39,174,96,.12);color:#27AE60;border-radius:20px;font-size:.7rem;font-weight:700;">Hadir</span>
                                @else
                                    <span
                                        style="display:inline-block;padding:3px 10px;background:rgba(192,57,43,.12);color:#C0392B;border-radius:20px;font-size:.7rem;font-weight:700;">Tidak
                                        Hadir</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" style="text-align:center;color:var(--text-muted);padding:1.5rem;">
                                Belum ada peserta.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Upload Bukti Hadir — view only --}}
        <div class="upload-wrap">
            <span class="upload-label">Upload Bukti Hadir</span>

            @if ($attendance && $attendance->photo_url)
                <img src="{{ $attendance->photo_url }}" class="uploaded-img" alt="Bukti Hadir">
            @else
                <div
                    style="text-align:center;padding:1.5rem;color:var(--text-muted);font-size:.82rem;border:1.5px dashed var(--border);border-radius:10px;">
                    Belum ada bukti hadir.
                </div>
            @endif
        </div>

    </div>
@endsection
