@extends('admin.layouts.app', [
    'pageName' => 'New Safe Transaction',
])

@section('content')
<div class="row">

    <div class="col-sm-12">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">New Transaction for: {{ $safe->name }}</h3>
            </div>

            <div class="card-body">

                @include('admin.layouts.partials._flash')

                <form action="{{ route('admin.safe-transactions.store', $safe->id) }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Amount</label>
                        <input type="number" step="0.01" name="amount"
                               class="form-control" value="{{ old('amount') }}">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description"
                                  class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <button class="btn btn-primary">Save</button>
                </form>

            </div>

        </div>
    </div>

</div>
@endsection
