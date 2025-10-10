@extends('admin.layouts.app', [
    'pageName' => 'Units',
])

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Units</h1>
        <a href="{{ route('admin.units.create') }}" class="btn btn-primary">Add New Unit</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped" id="units-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($units as $unit)
                <tr id="row-{{ $unit->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $unit->name }}</td>
                    <td>
                        <span
                         class="badge {{ $unit->status == \App\Enums\UnitStatusEnum::active->value ? 'bg-success' : 'bg-secondary' }}">
                         {{ \App\Enums\UnitStatusEnum::labels()[$unit->status] ?? 'Unknown' }}
                        </span>
                    </td>
                    <td>{{ $unit->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('admin.units.edit', $unit->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST" class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm btn-delete" type="submit"><i class="fas fa-trash"></i></button>
                        </form>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No Units found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


@endsection
