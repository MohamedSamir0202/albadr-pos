@extends('admin.layouts.app', ['pageName' => 'Roles Management'])

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Roles</h3>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-primary float-end">Add Role</a>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach($role->permissions as $perm)
                                <span class="badge bg-info">{{ $perm->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this role?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
