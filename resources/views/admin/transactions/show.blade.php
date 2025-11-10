@extends('admin.layouts.app', ['pageName' => 'Warehouse Details'])

@section('content')
<div class="container">
    <h2>Warehouse Details</h2>

    <div class="card mb-4">
        <div class="card-body d-flex align-items-center">
            @if ($warehouse->image)
                <img src="{{ asset('storage/' . $warehouse->image->path) }}"
                     alt="{{ $warehouse->name }}" width="100" class="rounded me-3">
            @else
                <img src="{{ asset('images/no-image.png') }}" width="100" class="rounded me-3">
            @endif

            <div>
                <h4>{{ $warehouse->name }}</h4>
                <p class="text-muted">{{ $warehouse->description ?: 'No description available.' }}</p>
                <p>
                    <strong>Status:</strong>
                    @if ($warehouse->status == 1)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <h4>Stocks in this Warehouse</h4>

    @if ($warehouse->stocks->count() > 0)
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($warehouse->stocks as $index => $stock)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $stock->item->name ?? 'Unknown Item' }}</td>
                        <td>{{ $stock->quantity }}</td>
                        <td>{{ $stock->unit ?? '—' }}</td>
                        <td>{{ $stock->updated_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">No items found in this warehouse yet.</p>
    @endif

    <a href="{{ route('admin.warehouses.index') }}" class="btn btn-secondary mt-3">Back to List</a>
    <a href="{{ route('admin.warehouses.edit', $warehouse->id) }}" class="btn btn-primary mt-3">Edit Warehouse</a>
</div>
@endsection

