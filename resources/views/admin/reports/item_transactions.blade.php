@extends('admin.layouts.app',[
    'pageName'=> 'Item Transactions',
])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">Filter</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reports.item_transactions') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Item</label>
                                    <select name="item_id" class="form-control select2">
                                        <option value="">All Items</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" {{ request('item_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Client</label>
                                    <select name="client_id" class="form-control select2">
                                        <option value="">All Clients</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Item Transactions</h3>
                </div>
                <div class="card-body p-0">
                    @include('admin.layouts.partials._flash')
                    <table class="table table-hover table-striped table-bordered mb-0">
                        <thead class="thead-light">
                        <tr>
                            <th>Created At</th>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Balance After</th>
                            <th>By</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($transactions as $transaction)
                            @foreach($transaction->items as $item)
                                <tr>
                                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                    <td><strong>{{ $item->name }}</strong></td>
                                    <td>
                                        <span class="badge {{ $transaction->type == \App\Enums\SaleTypeEnum::sale->value ? 'badge-success' : 'badge-danger' }}">
                                            {{ $transaction->type == \App\Enums\SaleTypeEnum::sale->value ? 'Sale Invoice' : 'Return Invoice' }}
                                        </span>
                                        <br>
                                        <small>Client: {{ $transaction->client->name ?? '-' }}</small>
                                        <br>
                                        <small>Invoice No: {{ $transaction->invoice_number }}</small>
                                    </td>
                                    <td class="text-center">{{ $item->pivot->quantity }}</td>
                                    <td>{{ number_format($item->pivot->unit_price, 2) }}</td>
                                    <td class="text-info font-weight-bold">
                                        @php
                                            $warehouseLog = $transaction->warehouseTransactions
                                                            ->where('item_id', $item->id)
                                                            ->first();
                                        @endphp
                                        {{ $warehouseLog ? $warehouseLog->quantity_after : "-" }}
                                    </td>
                                    <td>{{ $transaction->user->full_name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
