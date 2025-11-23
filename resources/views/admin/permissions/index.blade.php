@extends('admin.layouts.app', ['pageName' => 'Permissions'])

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Permissions</h3>
        <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary float-end">Add Permission</a>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Permission Name</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this permission?')">
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
