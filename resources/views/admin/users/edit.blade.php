@extends('admin.layouts.app', [
    'pageName' => 'Users',
])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users Edit</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" id="main-form">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input
                                class="form-control @error('username') is-invalid @enderror"
                                id="username"
                                placeholder="Enter username"
                                name="username"
                                value="{{ old('username', $user->username) }}"
                            >
                            @error('username')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="full_name">Full name</label>
                            <input class="form-control"
                                   id="full_name"
                                   placeholder="Enter username"
                                   name="full_name"
                                   value="{{ old('full_name', $user->full_name) }}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input
                                name="email"
                                type="email"
                                class="form-control"
                                id="email"
                                placeholder="Enter email"
                                value="{{ old('email', $user->email) }}"
                            >
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Password</label>
                            <input name="password" type="password" class="form-control" id="exampleInputPassword1"
                                   placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Password</label>
                            <input name="password_confirmation" type="password" class="form-control"
                                   id="password_confirmation" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="user_status">Status</label>
                            @foreach($userStatuses as $value => $label)
                                <div class="form-check">
                                    <input class="form-check-input"
                                           id="user_status"
                                           type="radio"
                                           name="status"
                                           value="{{ $value }}"
                                           @if($user->status->value == $value) checked @endif>
                                    <label for="user_status" class="form-check-label">{{ $label }}</label>
                                </div>
                            @endforeach

                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <x-form-submit text="Update"></x-form-submit>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
