@extends('admin.layouts.app', [
    'pageName' => 'Items',
])


@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center ">
                    <h3 class="card-title">Items List</h3>
                    <a href="{{ route('admin.items.create') }}" class="btn btn-primary">Add New Item</a>
                </div>
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Item Code</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Min Stock</th>
                            <th>Shown in Store</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($items as $item)
                            <tr id="row-{{ $item->id }}">
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->item_code }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->category?->name ?? '-' }}</td>
                                <td>{{ $item->unit?->name ?? '-' }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->minimum_stock }}</td>
                                <td>
                                    @if($item->is_shown_in_store)
                                        <span class="badge badge-success">Yes</span>
                                    @else
                                        <span class="badge badge-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $item->status == \App\Enums\ItemStatusEnum::active->value ? 'bg-success' : 'bg-secondary' }}">
                                        {{ \App\Enums\ItemStatusEnum::labels()[$item->status] ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.items.edit', $item->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>

                                    <form action="{{ route('admin.items.destroy', $item->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm btn-delete" type="submit"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">No items found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $items->links() }}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function () {
                if (!confirm('Are you sure you want to delete this item?')) return;

                const id = this.getAttribute('data-id');
                fetch(`/admin/items/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            document.getElementById(`row-${id}`).remove();
                            alert(data.message);
                        }
                    });
            });
        });
    });
</script>
@endpush
