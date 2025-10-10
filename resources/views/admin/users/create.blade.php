@extends('admin.layouts.app', [
    'pageName' => 'Users',
])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Users Create</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}" id="main-form">
                        @csrf
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input class="form-control @error('username') is-invalid @enderror" id="username" placeholder="Enter username" name="username">
                            @error('username')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="full_name">Full name</label>
                            <input class="form-control" id="full_name" placeholder="Enter username" name="full_name">
                        </div>
                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input name="email" type="email" class="form-control" id="email" placeholder="Enter email">
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
                            <label>Status</label>
                            @foreach($userStatuses as $value => $label)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="{{ $value }}"
                                           @if($loop->first) checked @endif>
                                    <label class="form-check-label">{{ $label }}</label>
                                </div>
                            @endforeach

                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    <x-form-submit text="Create"></x-form-submit>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
