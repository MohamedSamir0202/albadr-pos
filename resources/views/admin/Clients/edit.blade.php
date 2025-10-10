@extends('admin.layouts.app', [
    'pageName' => 'Clients',
])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Client</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.clients.update', $client->id) }}" id="main-form">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Name</label>
                            <input
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                placeholder="Enter client name"
                                name="name"
                                value="{{ old('name', $client->name) }}"
                            >
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                placeholder="Enter client email"
                                name="email"
                                value="{{ old('email', $client->email) }}"
                            >
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input
                                class="form-control @error('phone') is-invalid @enderror"
                                id="phone"
                                placeholder="Enter client phone"
                                name="phone"
                                value="{{ old('phone', $client->phone) }}"
                            >
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="address">Address</label>
                            <input
                                class="form-control @error('address') is-invalid @enderror"
                                id="address"
                                placeholder="Enter client address"
                                name="address"
                                value="{{ old('address', $client->address) }}"
                            >
                            @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="balance">Balance</label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('balance') is-invalid @enderror"
                                id="balance"
                                placeholder="Enter client balance"
                                name="balance"
                                value="{{ old('balance', $client->balance) }}"
                            >
                            @error('balance')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            @foreach($clientStatuses as $value => $label)
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="radio"
                                        name="status"
                                        value="{{ $value }}"
                                        @if(old('status', $client->status) == $value) checked @endif
                                    >
                                    <label class="form-check-label">{{ $label }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <label for="registered_via">Registered Via</label>
                            <select
                                class="form-control @error('registered_via') is-invalid @enderror"
                                id="registered_via"
                                name="registered_via"
                            >
                                <option value="">-- Select --</option>
                                <option value="pos" {{ old('registered_via', $client->registered_via) == 'pos' ? 'selected' : '' }}>POS</option>
                                <option value="app" {{ old('registered_via', $client->registered_via) == 'app' ? 'selected' : '' }}>App</option>
                            </select>
                            @error('registered_via')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
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
