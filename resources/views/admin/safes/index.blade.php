@extends('admin.layouts.app', [
    'pageName' => 'Safes',
])

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Safes List</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.safes.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Create
                    </a>
                </div>
            </div>

            <div class="card-body">
                @include('admin.layouts.partials._flash')

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Name</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($safes as $safe)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $safe->name }}</td>
                                <td>{{ number_format($safe->balance, 2) }}</td>

                                <td>
                                    <span class="badge badge-{{ $safe->status->style() }}">
                                        {{ $safe->status->label() }}
                                    </span>
                                </td>

                                <td>{{ $safe->type }}</td>
                                <td>{{ Str::limit($safe->description, 30) }}</td>

                                <td>
                                    <a href="{{ route('admin.safes.edit', $safe->id) }}"
                                       class="btn btn-success btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="#"
                                       data-url="{{ route('admin.safes.destroy', $safe->id) }}"
                                       data-id="{{ $safe->id }}"
                                       class="btn btn-danger btn-sm delete-button">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                    <a href="{{ route('admin.safes.show', $safe->id) }}"
                                       class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="card-footer clearfix">
                {{ $safes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
