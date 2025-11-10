@extends('admin.layouts.app', ['pageName' => 'Edit Warehouse'])

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">✏️ Edit Warehouse</h2>
        <a href="{{ route('admin.warehouses.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.warehouses.update', $warehouse->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Warehouse Name</label>
                    <input type="text" name="name" class="form-control" value="{{ $warehouse->name }}" required>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control">{{ $warehouse->description }}</textarea>
                </div>

                {{-- Current Image --}}
                @if ($warehouse->image)
                    <div class="mb-3">
                        <label class="form-label fw-semibold d-block">Current Image</label>
                        <img src="{{ asset('storage/' . $warehouse->image->path) }}" width="120" class="rounded shadow-sm mb-2">
                    </div>
                @endif

                {{-- New Image --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Change Image</label>
                    <input type="file" name="image" class="form-control">
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    @foreach($warehouseStatuses as $value => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" value="{{ $value }}"
                                   @if($warehouse->status == $value) checked @endif>
                            <label class="form-check-label">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>

                {{-- Update --}}
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Update Warehouse
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
