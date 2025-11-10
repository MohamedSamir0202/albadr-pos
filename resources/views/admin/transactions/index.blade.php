@extends('admin.layouts.app', ['pageName' => 'Warehouse Transactions'])

@section('content')
<div class="container py-4" dir="ltr">

    <div class="card shadow border-0 mb-4">
        <div class="card-header bg-gradient bg-primary text-white">
            <h5 class="mb-0">Warehouse Transactions</h5>
        </div>

        <div class="card-body">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Warehouse</label>
                    <select name="warehouse_id" class="form-select">
                        <option value="">All</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}" {{ request('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Item</label>
                    <select name="item_id" class="form-select">
                        <option value="">All</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">Transaction Type</label>
                    <select name="transaction_type" class="form-select">
                        <option value="">All</option>
                        @foreach($transactionTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('transaction_type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.warehouse-transactions.index') }}" class="btn btn-secondary">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow border-0">
        <div class="card-header bg-gradient bg-secondary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Transaction History</h6>
            <a href="#" class="btn btn-light btn-sm"><i class="bi bi-printer"></i> Print</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Warehouse</th>
                        <th>Item</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>After</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $trx->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $trx->warehouse->name ?? '-' }}</td>
                            <td>{{ $trx->item->name ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $trx->transaction_type->style() }}">
                                    {{ $trx->transaction_type->label() }}
                                </span>
                            </td>
                            <td class="text-end">{{ number_format($trx->quantity, 2) }}</td>
                            <td class="text-end">{{ number_format($trx->quantity_after, 2) }}</td>
                            <td>{{ $trx->description }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-3">
                                No transactions found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $transactions->links() }}
        </div>
    </div>

</div>
@endsection
