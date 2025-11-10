@extends('admin.layouts.app', ['pageName' => 'Add Warehouse'])

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">🏗️ Add New Warehouse</h2>
        <a href="{{ route('admin.warehouses.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.warehouses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Name --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Warehouse Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter warehouse name" required>
                </div>

                {{-- Description --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Description</label>
                    <textarea name="description" class="form-control" placeholder="Write a short description"></textarea>
                </div>

                {{-- Image --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Image</label>
                    <input type="file" name="image" class="form-control">
                </div>

                {{-- Status --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    @foreach($warehouseStatuses as $value => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" value="{{ $value }}"
                                   @if($loop->first) checked @endif>
                            <label class="form-check-label">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Save Warehouse
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
