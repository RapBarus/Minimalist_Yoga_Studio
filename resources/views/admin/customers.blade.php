@extends('layouts.admin')

@section('title', 'Data Pelanggan | Minimalist Studio')
@section('page-title', 'Data Pelanggan')
@section('page-sub', 'Daftar customer studio')

@push('styles')
    <style>
        .search-wrap {
            position: relative;
        }

        .search-wrap svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            stroke: var(--text-muted);
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .search-input {
            width: 100%;
            padding: .72rem .9rem .72rem 2.4rem;
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 12px;
            font-family: 'Raleway', sans-serif;
            font-size: .85rem;
            color: var(--text);
            outline: none;
            transition: border-color .2s;
        }

        .search-input:focus {
            border-color: var(--clay);
        }

        .search-input::placeholder {
            color: var(--text-muted);
        }

        .customer-table-wrap {
            background: var(--bg-white);
            border: 1.5px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .customer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .customer-table thead tr {
            background: var(--clay);
            color: #fff;
        }

        .customer-table th {
            padding: 12px 16px;
            text-align: left;
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .customer-table th:last-child {
            width: 48px;
            text-align: center;
        }

        .customer-table td {
            padding: 12px 16px;
            font-size: .82rem;
            color: var(--text);
            border-bottom: 1px solid var(--border);
        }

        .customer-table tr:last-child td {
            border-bottom: none;
        }

        .customer-table tr:hover td {
            background: #faf8f6;
        }

        .btn-edit-customer {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: var(--clay);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border-radius: 6px;
            transition: background .15s;
            text-decoration: none;
        }

        .btn-edit-customer:hover {
            background: var(--clay-pale);
        }

        .btn-edit-customer svg {
            width: 15px;
            height: 15px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--text-muted);
            font-size: .85rem;
        }
    </style>
@endpush

@section('content')
    <div class="content">

        {{-- Search --}}
        <div class="search-wrap">
            <svg viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="text" class="search-input" id="search-input" placeholder="Search"
                onkeyup="filterCustomers(this.value)">
        </div>

        {{-- Table --}}
        <div class="customer-table-wrap">
            <table class="customer-table">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="customer-tbody">
                    @forelse($customers as $customer)
                        <tr class="customer-row" data-name="{{ strtolower($customer->name) }}">
                            <td>{{ $customer->name }}</td>
                            <td>
                                <a href="{{ route('admin.customers.detail', $customer->user_id) }}"
                                    class="btn-edit-customer">
                                    <svg viewBox="0 0 24 24">
                                        <path d="M12 20h9" />
                                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="empty-state">Belum ada pelanggan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function filterCustomers(query) {
            const rows = document.querySelectorAll('.customer-row');
            const q = query.toLowerCase();
            rows.forEach(row => {
                row.style.display = row.dataset.name.includes(q) ? '' : 'none';
            });
        }
    </script>
@endpush
