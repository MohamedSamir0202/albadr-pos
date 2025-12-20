@extends('admin.layouts.app', ['pageName' => 'Sales Invoices'])

@section('content')
<div class="container py-4">

    {{-- Filters --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row gy-2 gx-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Client</label>
                    <select name="client_id" class="form-select form-select-sm">
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Payment Type</label>
                    <select name="payment_type" class="form-select form-select-sm">
                        <option value="">All</option>
                        <option value="1" {{ request('payment_type') == 1 ? 'selected' : '' }}>Cash</option>
                        <option value="2" {{ request('payment_type') == 2 ? 'selected' : '' }}>Debt</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">Invoice #</label>
                    <input type="text" name="invoice_number" value="{{ request('invoice_number') }}" class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search">Search</i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Sales Table --}}
    <div class="card shadow border-0">
        <div class="card-header bg-gradient bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Sales Invoices</h6>
            <a href="{{ route('admin.sales.create') }}" class="btn btn-info btn-sm">
                 New Sale
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle text-center mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Invoice #</th>
                        <th>Client</th>
                        <th>Total</th>
                        <th>Paid</th>
                        <th>Remaining</th>
                        <th>Payment Type</th>
                        <th>Created By</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $sale->invoice_number }}</td>
                            <td>{{ $sale->client->name ?? '-' }}</td>
                            <td class="text-success">{{ number_format($sale->total, 2) }}</td>
                            <td class="text-primary">{{ number_format($sale->paid_amount, 2) }}</td>
                            <td class="text-danger">{{ number_format($sale->remaining_amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $sale->payment_type == 1 ? 'success' : 'warning' }}">
                                    {{ $sale->payment_type == 1 ? 'Cash' : 'Debt' }}
                                </span>
                            </td>
                            <td>{{ $sale->user->username ?? 'System' }}</td>
                            <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.sales.show', $sale->id) }}" class="btn btn-sm btn-info text-white">
                                    Show
                                </a>
                                
                                <form action="{{ route('admin.sales.destroy', $sale->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this sale?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-3">No sales found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $sales->links() }}
        </div>
    </div>
</div>
@endsection
