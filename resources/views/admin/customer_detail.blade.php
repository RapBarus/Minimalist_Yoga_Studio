@extends('layouts.admin')

@section('title', 'Data Customer | Minimalist Studio')
@section('page-title', 'Data Customer')
@section('page-sub', 'Detail informasi pelanggan')

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
            margin-bottom: 16px;
        }

        .info-field label {
            display: block;
            font-size: .72rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        .info-field-value {
            width: 100%;
            padding: .75rem .9rem;
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            color: var(--text);
            display: block;
        }

        .section-label-page {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 10px;
            margin-top: 8px;
        }

        /* ── Activity cards ── */
        .activity-card {
            background: var(--clay);
            border-radius: 14px;
            padding: 16px 18px;
            color: #fff;
            position: relative;
            overflow: hidden;
            margin-bottom: 12px;
        }

        .activity-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 90px;
            height: 90px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
        }

        .ac-title {
            font-weight: 700;
            font-size: .95rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .ac-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255, 255, 255, .15);
            border-radius: 20px;
            padding: 3px 10px 3px 3px;
            font-size: .73rem;
            margin-bottom: 8px;
        }

        .ac-badge-avatar {
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

        .ac-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            flex-wrap: wrap;
            gap: 6px;
        }

        .ac-price-group {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .ac-price-old {
            font-size: .75rem;
            opacity: .65;
            text-decoration: line-through;
        }

        .ac-price-new {
            font-weight: 700;
            font-size: .9rem;
        }

        .ac-pill {
            font-size: .72rem;
            background: rgba(255, 255, 255, .15);
            padding: 3px 12px;
            border-radius: 20px;
            white-space: nowrap;
            font-weight: 600;
        }

        .btn-ac-danger {
            width: 100%;
            padding: .6rem;
            background: rgba(220, 38, 38, .35);
            color: #fff;
            border: 1.5px solid rgba(220, 38, 38, .5);
            border-radius: 8px;
            font-family: 'Raleway', sans-serif;
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background .15s;
        }

        .btn-ac-danger:hover {
            background: rgba(220, 38, 38, .55);
        }

        /* ── Booking history table ── */
        .booking-table {
            width: 100%;
            border-collapse: collapse;
            font-size: .82rem;
        }

        .booking-table thead tr {
            background: var(--clay);
            color: #fff;
        }

        .booking-table th {
            padding: 10px 14px;
            text-align: left;
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .booking-table th:first-child {
            border-radius: 10px 0 0 10px;
        }

        .booking-table th:last-child {
            border-radius: 0 10px 10px 0;
        }

        .booking-table td {
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            color: var(--text);
        }

        .booking-table tr:last-child td {
            border-bottom: none;
        }

        .booking-table tr:hover td {
            background: #faf8f6;
        }

        /* ── Confirm modal ── */
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

        .modal-confirm {
            background: #fff;
            border-radius: 18px;
            width: 100%;
            max-width: 320px;
            padding: 28px 24px;
            text-align: center;
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

        .modal-confirm-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
        }

        .modal-confirm-icon svg {
            width: 26px;
            height: 26px;
            stroke: var(--text-muted);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        .modal-confirm-text {
            font-size: .88rem;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .modal-confirm-btns {
            display: flex;
            gap: 10px;
        }

        .btn-confirm-yes {
            flex: 1;
            padding: .75rem;
            background: #dc2626;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .18s;
        }

        .btn-confirm-yes:hover {
            background: #b91c1c;
        }

        .btn-confirm-no {
            flex: 1;
            padding: .75rem;
            background: #f5f5f5;
            color: var(--text);
            border: none;
            border-radius: 10px;
            font-family: 'Raleway', sans-serif;
            font-size: .82rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .18s;
        }

        .btn-confirm-no:hover {
            background: #e5e5e5;
        }
    </style>
@endpush

@section('content')
    <div class="content">

        <a href="{{ route('admin.customers') }}" class="back-btn">
            <svg viewBox="0 0 24 24">
                <polyline points="15 18 9 12 15 6" />
            </svg>
            Kembali
        </a>

        <div class="info-field">
            <label>Nama Customer</label>
            <div class="info-field-value">{{ $customer->name }}</div>
        </div>

        <div class="info-field">
            <label>No Telpon</label>
            <div class="info-field-value">{{ $customer->phone_number ?? '—' }}</div>
        </div>

        <div class="info-field">
            <label>Tanggal Pendaftaran Akun</label>
            <div class="info-field-value">
                {{ \Carbon\Carbon::parse($customer->created_at)->translatedFormat('d F Y') }}
            </div>
        </div>

        {{-- Aktivitas --}}
        @if ($memberships->count() > 0 || $activeBookings->count() > 0)
            <div class="section-label-page">Aktivitas</div>

            {{-- Membership Cards --}}
            @foreach ($memberships as $m)
                <div class="activity-card">
                    <div class="ac-title">{{ $m->package_name }}</div>

                    @if ($m->class_name)
                        <div class="ac-badge">
                            <div class="ac-badge-avatar">M</div>
                            {{ $m->class_name }}
                        </div>
                    @endif

                    <div class="ac-footer">
                        <div style="font-size:.78rem;opacity:.85;">
                            {{ \Carbon\Carbon::parse($m->start_date)->format('d M Y') }} –
                            {{ \Carbon\Carbon::parse($m->reset_date)->format('d M Y') }}
                        </div>
                        <span class="ac-pill">pertemuan : {{ $m->total_quota - $m->used_quota }} /
                            {{ $m->total_quota }}</span>
                    </div>

                    <div class="ac-footer" style="margin-bottom:12px;">
                        <div class="ac-price-group">
                            @if ($m->original_price)
                                <span class="ac-price-old">Rp {{ number_format($m->original_price, 0, ',', '.') }}</span>
                            @endif
                            <span class="ac-price-new">Rp {{ number_format($m->price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button class="btn-ac-danger" onclick="openConfirm('membership', {{ $m->quota_id }})">
                        Hentikan Membership
                    </button>
                </div>
            @endforeach

            {{-- Active Booking Cards --}}
            @foreach ($activeBookings as $ab)
                <div class="activity-card">
                    <div class="ac-title">{{ $ab->class_name }}</div>

                    <div class="ac-badge">
                        <div class="ac-badge-avatar">{{ strtoupper(substr($ab->coach_name, 0, 1)) }}</div>
                        {{ $ab->coach_name }}
                    </div>

                    <div class="ac-footer">
                        <div style="font-size:.78rem;opacity:.85;">
                            {{ \Carbon\Carbon::parse($ab->schedule_date)->translatedFormat('l') }}
                        </div>
                        <div style="font-size:.78rem;opacity:.85;">
                            {{ \Carbon\Carbon::parse($ab->start_time)->format('H:i') }} –
                            {{ \Carbon\Carbon::parse($ab->end_time)->format('H:i') }} WIB
                        </div>
                    </div>

                    <div class="ac-footer" style="margin-bottom:12px;">
                        <div class="ac-price-group">
                            <span class="ac-price-new">Rp {{ number_format($ab->amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button class="btn-ac-danger" onclick="openConfirm('booking', {{ $ab->booking_id }})">
                        Cancel Booking
                    </button>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        let pendingType = null;
        let pendingId = null;

        function openConfirm(type, id) {
            pendingType = type;
            pendingId = id;
            const text = type === 'membership' ?
                'Apakah kamu yakin ingin menghentikan Membership ini?' :
                'Apakah kamu yakin ingin membatalkan Booking ini?';
            document.getElementById('modal-confirm-text').textContent = text;
            document.getElementById('modal-confirm').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeConfirm() {
            pendingType = null;
            pendingId = null;
            document.getElementById('modal-confirm').classList.remove('open');
            document.body.style.overflow = '';
        }

        document.getElementById('btn-confirm-yes').addEventListener('click', function() {
            if (!pendingType || !pendingId) return;
            if (pendingType === 'membership') {
                document.getElementById('stop-quota-id').value = pendingId;
                document.getElementById('form-stop-membership').submit();
            } else {
                document.getElementById('cancel-booking-id').value = pendingId;
                document.getElementById('form-cancel-booking').submit();
            }
        });
    </script>
@endpush

{{-- Confirm Modal --}}
<div class="modal-overlay" id="modal-confirm">
    <div class="modal-confirm">
        <div class="modal-confirm-icon">
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
        </div>
        <div class="modal-confirm-text" id="modal-confirm-text"></div>
        <div class="modal-confirm-btns">
            <button class="btn-confirm-yes" id="btn-confirm-yes">Ya</button>
            <button class="btn-confirm-no" onclick="closeConfirm()">Batal</button>
        </div>
    </div>
</div>

<form id="form-stop-membership" action="{{ route('admin.customers.stop-membership') }}" method="POST"
    style="display:none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="quota_id" id="stop-quota-id">
    <input type="hidden" name="user_id" value="{{ $customer->user_id }}">
</form>

<form id="form-cancel-booking" action="{{ route('admin.customers.cancel-booking') }}" method="POST"
    style="display:none;">
    @csrf
    @method('DELETE')
    <input type="hidden" name="booking_id" id="cancel-booking-id">
    <input type="hidden" name="user_id" value="{{ $customer->user_id }}">
</form>
