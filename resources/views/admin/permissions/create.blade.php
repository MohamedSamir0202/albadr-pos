@extends('admin.layouts.app', ['pageName' => 'Add Permission'])

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Create New Permission</h3>
    </div>

    <form action="{{ route('admin.permissions.store') }}" method="POST">
        @csrf
        <div class="card-body">
            <div class="form-group mb-3">
                <label for="name">Permission Name</label>
                <input type="text" name="name" id="name"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Enter permission name" value="{{ old('name') }}">
                @error('name')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="card-footer text-end">
            <x-form-submit text="Create Permission"></x-form-submit>
        </div>
    </form>
</div>
@endsection
