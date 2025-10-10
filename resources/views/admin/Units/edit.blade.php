@extends('admin.layouts.app', [
    'pageName' => 'Units',
])

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Unit Edit</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.units.update', $unit->id) }}" id="main-form">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input name="name" type="text" class="form-control"
                                   id="name" placeholder="Name" value="{{ old('name', $unit->name) }}">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                         <div class="form-group">
                            <label for="unit_status">Status</label>
                            <select name="status" class="form-control">
                                @foreach(\App\Enums\UnitStatusEnum::cases() as $case)
                                    <option value="{{ $case->value }}"
                                        @selected(old('status', $unit->status) == $case->value)>
                                        {{ $case->label() }}
                                    </option>
                                @endforeach
                           </select>


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
