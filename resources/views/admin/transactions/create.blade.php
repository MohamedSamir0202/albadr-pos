@extends('admin.layouts.app', [
    'pageName' => 'Add Stock Item'
    ])

@section('content')
<div class="container">
    <h2>Add Stock to {{ $warehouse->name }}</h2>

    <form action="{{ route('admin.stocks.store', $warehouse->id) }}" method="POST">
    @csrf

        <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">

        <div class="mb-3">
            <label>Item</label>
            <select name="item_id" class="form-control" required>
                <option value="">Select Item</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Unit</label>
            <select name="unit_id" class="form-control">
                <option value="">Select Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Quantity</label>
            <input type="number" step="0.01" name="quantity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Minimum Quantity (Optional)</label>
            <input type="number" step="0.01" name="min_quantity" class="form-control">
        </div>

        <div class="mb-3">
            <label>Note</label>
            <textarea name="note" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('admin.stocks.index', $warehouse->id) }}" class="btn btn-secondary">Cancel</a>
    </form>

</div>
@endsection
