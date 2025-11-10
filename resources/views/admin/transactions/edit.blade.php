@extends('admin.layouts.app', [
    'pageName' => 'Edit Stock Item'
])

@section('content')
<div class="container">
    <h2>Edit Stock for {{ $warehouse->name }}</h2>

    <form action="{{ route('admin.stocks.update', [$warehouse->id, $stock->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">

        <div class="mb-3">
            <label>Item</label>
            <select name="item_id" class="form-control" required>
                <option value="">Select Item</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}" {{ $stock->item_id == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Unit</label>
            <select name="unit_id" class="form-control">
                <option value="">Select Unit</option>
                @foreach($units as $unit)
                    <option value="{{ $unit->id }}" {{ $stock->unit_id == $unit->id ? 'selected' : '' }}>
                        {{ $unit->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Quantity</label>
            <input type="number" step="0.01" name="quantity" class="form-control"
                   value="{{ old('quantity', $stock->quantity) }}" required>
        </div>

        <div class="mb-3">
            <label>Minimum Quantity (Optional)</label>
            <input type="number" step="0.01" name="min_quantity" class="form-control"
                   value="{{ old('min_quantity', $stock->min_quantity) }}">
        </div>

        <div class="mb-3">
            <label>Note</label>
            <textarea name="note" class="form-control">{{ old('note', $stock->note) }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('admin.stocks.index', $warehouse->id) }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
