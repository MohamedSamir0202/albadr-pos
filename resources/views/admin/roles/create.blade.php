@extends('admin.layouts.app', [
    'pageName' => ('Create Role'),
])
@section('content')
<div class="container mt-4">

    <h3>Create New Role</h3>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Role Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <label class="form-label">Permissions</label>
        <div class="row">
            @foreach($permissions as $permission)
                <div class="col-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                               name="permissions[]" value="{{ $permission->id }}">
                        <label class="form-check-label">
                            {{ $permission->name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary mt-3">Create Role</button>
    </form>
</div>
@endsection
