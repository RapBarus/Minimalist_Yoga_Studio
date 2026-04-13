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

        .booking-section-label {
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 10px;
            margin-top: 8px;
        }

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

        {{-- Booking history --}}
        @if ($bookings->count() > 0)
            <div class="booking-section-label">Riwayat Booking</div>
            <div class="section-card" style="overflow:hidden;">
                <table class="booking-table">
                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->class_name }}</td>
                                <td>{{ \Carbon\Carbon::parse($booking->schedule_date)->format('d M Y') }}</td>
                                <td>
                                    <span
                                        class="badge badge-{{ $booking->status === 'confirmed' ? 'confirmed' : ($booking->status === 'attended' ? 'attended' : 'cancelled') }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
@endsection
