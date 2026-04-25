@extends('layouts.admin')

@section('title', 'Kelola Membership | Minimalist Studio')
@section('page-title', 'Membership')
@section('page-sub', 'Kelola paket membership studio')

@push('styles')
    <style>
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
            max-width: 420px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 24px;
            animation: modalIn .2s ease both;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: translateY(16px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
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
        .modal-field select,
        .modal-field textarea {
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
        .modal-field select:focus,
        .modal-field textarea:focus {
            border-color: var(--clay);
        }

        .modal-field-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
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

        .btn-add {
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
            cursor: pointer;
            transition: background .18s;
        }

        .btn-add:hover {
            background: var(--clay-dark);
        }

        .btn-add svg {
            width: 13px;
            height: 13px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
        }

        .list-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .list-title {
            font-weight: 700;
            font-size: .95rem;
            color: var(--text);
        }

        /* ── Package cards ── */
        .packages-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .package-card {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 14px;
            padding: 16px 18px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .package-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .package-name {
            font-weight: 700;
            font-size: .9rem;
            color: var(--text);
        }

        .package-desc {
            font-size: .72rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .package-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }

        .package-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 10px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 20px;
            font-size: .72rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .package-price {
            font-weight: 700;
            font-size: .9rem;
            color: var(--clay);
        }

        .package-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')
    <div class="content">

        <div class="list-header">
            <div class="list-title">Daftar Paket ({{ $packages->count() }})</div>
            <button class="btn-add" onclick="openModal('modal-add')">
                <svg viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Tambah Member
            </button>
        </div>

        <div class="packages-list">
            @forelse($packages as $package)
                <div class="package-card">
                    <div class="package-card-top">
                        <div>
                            <div class="package-name">{{ $package->name }}</div>
                            @if ($package->description)
                                <div class="package-desc">{{ $package->description }}</div>
                            @endif
                        </div>
                        <div class="package-actions">
                            <form action="{{ route('admin.membership.toggle', $package->package_id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="badge {{ $package->is_active ? 'badge-confirmed' : 'badge-cancelled' }}"
                                    style="border:none;cursor:pointer;">
                                    {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.membership.destroy', $package->package_id) }}" method="POST"
                                onsubmit="return confirm('Hapus paket ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger-sm">Hapus</button>
                            </form>
                        </div>
                    </div>

                    <div class="package-meta">
                        <span class="package-tag">{{ $package->quota_amount }}x sesi</span>
                        <span class="package-tag">{{ $package->validity_months }} bulan</span>
                        <span class="package-price">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            @empty
                <div style="text-align:center;color:var(--text-muted);padding:2rem;font-size:.85rem;">
                    Belum ada paket.
                </div>
            @endforelse
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

        @if ($errors->any())
            openModal('modal-add');
        @endif
    </script>
@endpush

<div class="modal-overlay" id="modal-add">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Tambah Paket Baru</div>
            <button class="modal-close" onclick="closeModal('modal-add')">
                <svg viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.membership.store') }}" method="POST" class="modal-form">
            @csrf

            <div class="modal-field">
                <label>Nama Membership</label>
                <input type="text" name="name" placeholder="contoh: Member Yoga With Nima"
                    value="{{ old('name') }}" required>
            </div>

            <div class="modal-field">
                <label>Coach</label>
                <select name="coach_id">
                    <option value="">Pilih Coach</option>
                    @foreach ($coaches as $coach)
                        <option value="{{ $coach->coach_id }}"
                            {{ old('coach_id') == $coach->coach_id ? 'selected' : '' }}>
                            {{ $coach->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="modal-field-row">
                <div class="modal-field">
                    <label>Jam Mulai</label>
                    <input type="time" name="start_time" value="{{ old('start_time') }}">
                </div>
                <div class="modal-field">
                    <label>Jam Selesai</label>
                    <input type="time" name="end_time" value="{{ old('end_time') }}">
                </div>
            </div>

            <div class="modal-field-row">
                <div class="modal-field">
                    <label>Harga Asli (Rp)</label>
                    <input type="number" name="original_price" placeholder="150000"
                        value="{{ old('original_price') }}" min="0" step="1000">
                </div>
                <div class="modal-field">
                    <label>Harga Diskon (Rp)</label>
                    <input type="number" name="price" placeholder="120000" value="{{ old('price') }}"
                        min="0" step="1000" required>
                </div>
            </div>

            <div class="modal-field">
                <label>Kuota Kelas (Jumlah Sesi)</label>
                <input type="number" name="quota_amount" placeholder="8" value="{{ old('quota_amount') }}"
                    min="1" required>
            </div>

            <div class="modal-field">
                <label>Masa Aktif (Bulan)</label>
                <input type="number" name="validity_months" placeholder="2" value="{{ old('validity_months', 2) }}"
                    min="1" required>
            </div>

            <div class="modal-field">
                <label>Deskripsi</label>
                <textarea name="description" rows="3" placeholder="Deskripsi paket...">{{ old('description') }}</textarea>
            </div>

            <button type="submit" class="btn-modal-submit">Tambah Paket</button>
        </form>
    </div>
</div>
