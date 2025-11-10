@extends('admin.layouts.app', [
    'pageName' => 'Categories',
])
@section('content')
<div class="container mt-4">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Categories</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            + Add Category
        </a>
    </div>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $index => $category)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $category->name }}</td>
                    <td>
                        <span
                         class="badge {{ $category->status == \App\Enums\CategoryStatusEnum::active->value ? 'bg-success' : 'bg-secondary' }}">
                         {{ \App\Enums\CategoryStatusEnum::labels()[$category->status->value] ?? 'Unknown' }}

                        </span>
                    </td>

                    <td>
                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                           class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>

                        <button class="btn btn-sm btn-danger btn-delete"
                                data-id="{{ $category->id }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">No categories found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Ajax Delete --}}
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            if(confirm('Are you sure you want to delete this category?')) {
                fetch(`/admin/categories/${this.dataset.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        location.reload();
                    } else {
                        alert('Failed to delete category');
                    }
                })
                .catch(() => alert('Something went wrong!'));
            }
        });
    });
</script>
@endsection

