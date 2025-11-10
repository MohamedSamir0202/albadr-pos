@extends('admin.layouts.app', ['pageName' => 'Client Account Statement'])

@section('content')
<div class="container py-4">

    {{-- Client Info --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Client Details</h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2 text-center">
                    <img src="{{ $client->image ? asset('storage/'.$client->image->path) : asset('images/no-image.png') }}"
                         alt="{{ $client->name }}" class="img-thumbnail rounded-circle" width="120">
                </div>
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> {{ $client->name }}</p>
                            <p><strong>Phone:</strong> {{ $client->phone ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Current Balance:</strong>
                                <span class="badge bg-success">{{ number_format($client->balance, 2) }}</span>
                            </p>
                            <p><strong>Total Transactions:</strong>
                                {{ $client->accountTransactions->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" class="row gy-2 gx-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="card shadow border-0">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Account Transactions</h6>
            <a href="#" class="btn btn-light btn-sm"><i class="bi bi-printer"></i> Print</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Description</th>
                        <th class="text-success">Credit</th>
                        <th class="text-danger">Debit</th>
                        <th>Balance After</th>
                        <th>User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $transaction->description }}</td>
                            <td class="text-success text-end">{{ number_format($transaction->credit, 2) }}</td>
                            <td class="text-danger text-end">{{ number_format($transaction->debit, 2) }}</td>
                            <td class="fw-semibold text-end">{{ number_format($transaction->balance_after, 2) }}</td>
                            <td>{{ $transaction->user->username ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="table-light fw-semibold">
                    <tr>
                        <td colspan="3" class="text-end">Totals:</td>
                        <td class="text-success text-end">{{ number_format($totals['totalCredit'], 2) }}</td>
                        <td class="text-danger text-end">{{ number_format($totals['totalDebit'], 2) }}</td>
                        <td colspan="2" class="text-end">
                            Current Balance:
                            <span class="badge bg-primary">{{ number_format($totals['finalBalance'], 2) }}</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
