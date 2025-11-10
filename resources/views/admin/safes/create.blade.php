@extends('admin.layouts.app', [
    'pageName' => 'Create Safe',
])

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">Create Safe</h3>
            </div>

            <div class="card-body">
                @include('admin.layouts.partials._flash')

                <form action="{{ route('admin.safes.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Name</label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label>Type</label>
                        <select name="type" class="form-control">
                            <option value="1">Main Safe</option>
                            <option value="2">Sub Safe</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Balance</label>
                        <input type="number" step="0.01"
                               name="balance"
                               class="form-control"
                               value="{{ old('balance') }}">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control">
                            @foreach(\App\Enums\SafeStatusEnum::labels() as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description"
                        class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <button class="btn btn-primary">Save</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
