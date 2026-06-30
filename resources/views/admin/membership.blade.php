@extends('layouts.admin')

@section('title', 'View Membership | Minimalist Studio')
@section('page-title', 'View Membership')
@section('page-sub', $package->name)

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

        .info-field-input {
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
        }

        .info-field-input:focus {
            border-color: var(--clay);
        }

        .info-field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
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
            margin-bottom: 24px;
            transition: background .18s;
        }

        .btn-save:hover {
            background: var(--clay-dark);
        }

        .peserta-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .peserta-title {
            font-weight: 700;
            font-size: .95rem;
            color: var(--text);
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
        }

        .status-inactive {
            display: inline-block;
            padding: 3px 10px;
            background: rgba(192, 57, 43, .12);
            color: #C0392B;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 700;
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

        <form action="{{ route('admin.membership.update', $package->package_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="info-field">
                <label>Nama Paket</label>
                <input class="info-field-input" type="text" name="name" value="{{ old('name', $package->name) }}"
                    required>
            </div>

            <div class="info-field">
                <label>Kelas</label>
                <select class="info-field-input" name="class_id" required>
                    <option value="">Pilih Kelas</option>
                    @foreach ($classes as $class)
                        <option value="{{ $class->class_id }}"
                            {{ $package->class_id == $class->class_id ? 'selected' : '' }}>
                            {{ $class->class_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="info-field">
                <label>Harga</label>
                <div class="info-field-row">
                    <input class="info-field-input" type="number" name="original_price" placeholder="Harga Asli"
                        value="{{ old('original_price', $package->original_price) }}" min="0" step="1000">
                    <input class="info-field-input" type="number" name="price" placeholder="Harga Diskon"
                        value="{{ old('price', $package->price) }}" min="0" step="1000" required>
                </div>
            </div>

            <div class="info-field">
                <label>Durasi Membership (Bulan)</label>
                <input class="info-field-input" type="number" name="validity_months"
                    value="{{ old('validity_months', $package->validity_months) }}" min="1" required>
            </div>

            <div class="info-field">
                <label>Kuota Anggota</label>
                <input class="info-field-input" type="number" name="quota_amount"
                    value="{{ old('quota_amount', $package->quota_amount) }}" min="1" required>
            </div>

            <div class="info-field">
                <label>Deskripsi</label>
                <textarea class="info-field-input" name="description" rows="3" placeholder="Deskripsi paket...">{{ old('description', $package->description) }}</textarea>
            </div>

            <button type="submit" class="btn-save">Simpan Perubahan</button>
        </form>

        {{-- Peserta Membership --}}
        <div class="peserta-header">
            <div class="peserta-title">Peserta Membership</div>
        </div>

        <div style="overflow:hidden;">
            <table class="peserta-table">
                <thead>
                    <tr>
                        <th>Peserta</th>
                        <th>Metode</th>
                        <th>Sisa Kuota</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peserta as $p)
                        <tr>
                            <td>{{ $p->name }}</td>
                            <td>{{ strtoupper($p->payment_channel ?? '—') }}</td>
                            <td>{{ $p->total_quota - $p->used_quota }} / {{ $p->total_quota }}</td>
                            <td>
                                @if ($p->is_active)
                                    <span class="status-valid">AKTIF</span>
                                @else
                                    <span class="status-inactive">NONAKTIF</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--text-muted);padding:2rem;">
                                Belum ada peserta.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
