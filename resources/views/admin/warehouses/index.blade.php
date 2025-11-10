@extends('admin.layouts.app', [
    'pageName' => 'Warehouses',
])

@section('content')
<div class="container py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            🏭 Warehouses
        </h2>
        <a href="{{ route('admin.warehouses.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add New Warehouse
        </a>
    </div>

    {{-- Table Card --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($warehouses as $warehouse)
                            <tr>
                                <td class="fw-semibold">{{ $loop->iteration }}</td>

                                {{-- Image --}}
                                <td>
                                    @if ($warehouse->image)
                                        <img src="{{ asset('storage/' . $warehouse->image->path) }}"
                                            alt="Warehouse Image" width="45" height="45"
                                            class="rounded border shadow-sm">
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>

                                {{-- Name & Description --}}
                                <td class="fw-semibold">{{ $warehouse->name }}</td>
                                <td class="text-muted" style="max-width: 250px;">{{ $warehouse->description ?? '—' }}</td>

                                {{-- Status --}}
                                <td>
                                    <span class="badge bg-{{ $warehouse->status->style() }}">
                                        {{ $warehouse->status->label() }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <a href="{{ route('admin.warehouses.show', $warehouse->id) }}"
                                        class="btn btn-sm btn-outline-info me-1">
                                        <i class="bi bi-eye"></i> View
                                    </a>

                                    <a href="{{ route('admin.warehouses.edit', $warehouse->id) }}"
                                        class="btn btn-sm btn-outline-warning me-1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>

                                    <form action="{{ route('admin.warehouses.destroy', $warehouse->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this warehouse?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    No warehouses found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="card-footer text-center">
            {{ $warehouses->links() }}
        </div>
    </div>
</div>
@endsection
