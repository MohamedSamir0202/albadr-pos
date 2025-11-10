@extends('admin.layouts.app', [
    'pageName' => 'Safe Transactions',
])

@section('content')
<div class="row">
    <div class="col-sm-12">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    Transactions — {{ $safe->name }} (Balance: {{ number_format($safe->balance, 2) }})
                </h3>

                <div class="card-tools">
                    <a href="{{ route('admin.safe-transactions.create', $safe->id) }}"
                       class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> New Transaction
                    </a>
                </div>
            </div>

            <div class="card-body">
                @include('admin.layouts.partials._flash')

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Balance After</th>
                            <th>User</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($transactions as $trx)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <span class="badge badge-{{ $trx->type->style() }}">
                                        {{ $trx->type->label() }}
                                    </span>
                                </td>

                                <td>{{ number_format($trx->amount, 2) }}</td>
                                <td>{{ $trx->description }}</td>
                                <td>{{ number_format($trx->balance_after, 2) }}</td>
                                <td>{{ $trx->user?->username }}</td>
                                <td>{{ $trx->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

            <div class="card-footer clearfix">
                {{ $transactions->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
