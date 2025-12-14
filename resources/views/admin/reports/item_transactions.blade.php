@extends('admin.layouts.app',[
    'pageName'=> __('trans.trans.item_transactions'),
    ])
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@lang('trans.item_transactions')</h3>
                    <div class="card-tools">

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @include('admin.layouts.partials._flash')
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>@lang('trans.by')</th>
                            <th>@lang('trans.current_quantity')</th>
                            <th >@lang('trans.description')</th>
                            <th>@lang('trans.price')</th>
                            <th>@lang('trans.quantity')</th>
                            <th>@lang('trans.created_at')</th>
                            <th>@lang('trans.item_name')</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $transaction)
                            @foreach($transaction->items as $item)
                                <tr>
                                    <td>{{ $transaction->user->full_name }}</td>
                                    <td>{{ $transaction->warehouseTransactions ? $transaction->warehouseTransactions->where('item_id', $item->id)->first()->quantity_after : "-"}}</td>
                                    <td>
                                        @if($transaction->isSale())
                                            فاتورة بيع
                                        @else
                                            فاتورة مرتجع
                                        @endif
                                        - اسم العميل :
                                        {{ $transaction->client->name }}
                                    </td>
                                    <td>
                                        {{ $item->pivot->total_price }}
                                    </td>
                                    <td>
                                        {{ $item->pivot->quantity }}
                                    </td>
                                    <td>
                                        {{ $transaction->created_at->toDateTimeString() }}
                                    </td>
                                    <td>
                                        {{ $item->name }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $transactions->links() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
