@extends('admin.layouts.app', [
    'pageName' => 'Units',
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
                    <form method="POST" action="{{ route('admin.units.store') }}" id="main-form">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input name="name" type="text" class="form-control"
                                   id="name" placeholder="Name" value="{{ old('name') }}">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        <div class="form-group">
                            <label>Status</label>
                            @foreach($unitStatuses as $value => $label)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" value="{{ $value }}"
                                           @if($loop->first) checked @endif>
                                    <label class="form-check-label">{{ $label }}</label>
                                </div>
                            @endforeach

                        </div>

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
