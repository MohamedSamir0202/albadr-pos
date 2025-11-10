@extends('admin.layouts.app', ['pageName' => 'Warehouse Details'])

@section('content')
<div class="container py-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">🏢 Warehouse Details</h2>
        <a href="{{ route('admin.warehouses.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    {{-- Warehouse Info --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body d-flex align-items-center">
            @if ($warehouse->image)
                <img src="{{ asset('storage/' . $warehouse->image->path) }}" width="100" class="rounded shadow-sm me-3">
            @else
                <img src="{{ asset('images/no-image.png') }}" width="100" class="rounded me-3 border">
            @endif

            <div>
                <h4 class="fw-bold mb-1">{{ $warehouse->name }}</h4>
                <p class="text-muted mb-2">{{ $warehouse->description ?? 'No description provided.' }}</p>
                <span class="badge bg-{{ $warehouse->status->style() }}">{{ $warehouse->status->label() }}</span>
            </div>
        </div>
    </div>

    {{-- Items Section --}}
    <h5 class="fw-bold mb-3">📦 Items in this Warehouse</h5>

    @if ($warehouse->items->count() > 0)
        <div class="card shadow-sm border-0 mb-5">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Last Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($warehouse->items as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $item->name ?? 'Unknown' }}</td>
                                <td>{{ $item->pivot->quantity }}</td>
                                <td>{{ $item->updated_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-secondary">No items found in this warehouse.</div>
    @endif

    {{-- Warehouse Transactions --}}
    <h5 class="fw-bold mb-3">🔄 Warehouse Transactions</h5>

    @if ($transactions->count() > 0)
        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Transaction Type</th>
                            <th>Reference</th>
                            <th>Quantity</th>
                            <th>Quantity After</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $index => $transaction)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->transaction_type->style() }}">
                                        {{ $transaction->transaction_type->label() }}
                                    </span>
                                </td>
                                <td>{{ class_basename($transaction->reference_type) }} #{{ $transaction->reference_id }}</td>
                                <td>{{ $transaction->quantity }}</td>
                                <td>{{ $transaction->quantity_after }}</td>
                                <td>{{ $transaction->description ?? '—' }}</td>
                                <td>{{ $transaction->created_at?->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-secondary">No transactions recorded for this warehouse.</div>
    @endif

</div>
@endsection
