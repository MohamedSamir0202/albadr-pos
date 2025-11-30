@extends('admin.layouts.app', [
    'pageName' => ('Roles'),
])
@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Roles List</h3>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Create New Role</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <strong>All Roles</strong>
        </div>

        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Role Name</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>

                            <td>
                                @if($role->permissions->count() > 0)
                                    @foreach($role->permissions as $permission)
                                        <span class="badge bg-info text-dark">{{ $permission->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No Permissions</span>
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
                                      class="d-inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this role?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted p-3">No roles found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $roles->links() }}
        </div>
    </div>
</div>
@endsection
