@extends('admin.layouts.app', [
    'pageName' => ('Edit Role'),
])

@section('content')
<div class="container mt-4">

    <h3>Edit Role</h3>

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="name" class="form-control"
                   value="{{ $role->name }}" required>
        </div>

        <label class="form-label">Permissions</label>
        <div class="row">
            @foreach($permissions as $permission)
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="permissions[]" value="{{ $permission->id }}"
                               @checked(in_array($permission->id, $rolePermissions))>

                        <label class="form-check-label">
                            {{ $permission->name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update Role</button>
    </form>
</div>
@endsection

