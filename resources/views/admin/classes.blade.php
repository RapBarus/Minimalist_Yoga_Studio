@extends('layouts.admin')

@section('title', 'Kelola Kelas | Minimalist Studio')
@section('page-title', 'Kelola Kelas')
@section('page-sub', 'Tambah dan kelola jenis kelas')

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
            resize: vertical;
        }

        .modal-field input:focus,
        .modal-field select:focus,
        .modal-field textarea:focus {
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
    </style>
@endpush

@section('content')
    <div class="content">

        <div class="list-header">
            <div class="list-title">Daftar Kelas ({{ $classes->count() }})</div>
            <button class="btn-add" onclick="openModal('modal-add')">
                <svg viewBox="0 0 24 24">
                    <line x1="12" y1="5" x2="12" y2="19" />
                    <line x1="5" y1="12" x2="19" y2="12" />
                </svg>
                Tambah Kelas
            </button>
        </div>

        <div class="section-card">
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Level</th>
                        <th>Durasi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $class)
                        <tr>
                            <td>
                                {{ $class->class_name }}<br>
                                <span
                                    style="font-size:.72rem;color:var(--text-muted)">{{ Str::limit($class->description, 40) }}</span>
                            </td>
                            <td>
                                <span
                                    class="badge badge-{{ $class->level === 'beginner' ? 'confirmed' : ($class->level === 'intermediate' ? 'attended' : 'pending') }}">
                                    {{ $class->level }}
                                </span>
                            </td>
                            <td>{{ $class->duration_minutes }} min</td>
                            <td>
                                <form action="{{ route('admin.classes.destroy', $class->class_id) }}" method="POST"
                                    onsubmit="return confirm('Hapus kelas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--text-muted);padding:2rem;">Belum ada
                                kelas.</td>
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
        @if ($errors->any())
            openModal('modal-add');
        @endif
    </script>
@endpush

<div class="modal-overlay" id="modal-add">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">Tambah Kelas Baru</div>
            <button class="modal-close" onclick="closeModal('modal-add')">
                <svg viewBox="0 0 24 24">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
        </div>
        <form action="{{ route('admin.classes.store') }}" method="POST" class="modal-form">
            @csrf
            <div class="modal-field">
                <label>Nama Kelas</label>
                <input type="text" name="class_name" placeholder="contoh: Yoga" value="{{ old('class_name') }}"
                    required>
            </div>
            <div class="modal-field">
                <label>Deskripsi</label>
                <textarea name="description" placeholder="Deskripsi kelas..." rows="3">{{ old('description') }}</textarea>
            </div>
            <div class="modal-field">
                <label>Level</label>
                <select name="level" required>
                    <option value="">-- Pilih Level --</option>
                    <option value="beginner" {{ old('level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                    <option value="intermediate" {{ old('level') === 'intermediate' ? 'selected' : '' }}>Intermediate
                    </option>
                    <option value="advanced" {{ old('level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>
            </div>
            <div class="modal-field">
                <label>Durasi (Menit)</label>
                <input type="number" name="duration_minutes" placeholder="60" value="{{ old('duration_minutes') }}"
                    min="1" required>
            </div>
            <button type="submit" class="btn-modal-submit">Tambah Kelas</button>
        </form>
    </div>
</div>
