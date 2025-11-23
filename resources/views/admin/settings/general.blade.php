@extends('admin.layouts.app', [
    'pageName' => 'General Settings',
])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">General Settings</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.settings.general.update') }}" id="main-form">
                        @csrf
                        @method('Put')
                        <div class="form-group">
                            <label for="name">Company Name</label>
                            <input name="company_name" type="text" class="form-control"
                            id="name" placeholder="Company Name"
                            value="{{ old('company_name',
                             $generalSettings->company_name) }}">
                            @error('company_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Company Email</label>
                            <input name="company_email" type="email" class="form-control"
                            id="name" placeholder="Company Email"
                            value="{{ old('company_email' ,
                             $generalSettings->company_email) }}">
                            @error('company_email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Company Phone</label>
                            <input name="company_phone" type="text" class="form-control"
                            id="name" placeholder="Company Phone"
                            value="{{ old('company_phone',
                             $generalSettings->company_phone) }}">
                            @error('company_phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="logo">Company Logo</label>
                            <input name="company_logo" type="file" class="form-control"
                            id="name"  >

                        </div>

                      <div>
                        <img src="{{ asset('storage/'.$generalSettings->company_logo) }}" height="70px" alt="">
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
