@extends('admin.layouts.app', ['pageName' => 'Edit Role'])

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Role: {{ $role->name }}</h3>
    </div>

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group mb-3">
                <label for="name">Role Name</label>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Enter role name" value="{{ old('name', $role->name) }}">
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <hr>

            <div class="form-group">
                <label>Assign Permissions</label>
                <div class="row">
                    @foreach($permissions as $permission)
                        <div class="col-sm-3 mb-2">
                            <div class="form-check">
                                <input type="checkbox" name="permissions[]"
                                       id="perm_{{ $permission->id }}"
                                       value="{{ $permission->name }}"
                                       class="form-check-input"
                                       @if($role->hasPermissionTo($permission->name)) checked @endif>
                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <button>Update</button>
    </form>
</div>
@endsection
