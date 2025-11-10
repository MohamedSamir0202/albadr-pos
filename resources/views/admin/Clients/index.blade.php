@extends('admin.layouts.app', [
    'pageName' => 'Clients',
])

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">

            {{-- Header Section --}}
            <div class="card-header py-3">
                <div class="row align-items-center text-center text-md-start">
                    {{-- Title --}}
                    <div class="col-md-4 mb-2 mb-md-0">
                        <h4 class="mb-0">Clients List</h4>
                    </div>

                    {{-- Search Form (Centered) --}}
                    <div class="col-md-4 mb-2 mb-md-0">
                        <form action="{{ route('admin.clients.search') }}" method="GET" class="d-flex justify-content-center">
                            <div class="input-group">
                                <input type="text" name="query" class="form-control form-control-sm" placeholder="Search clients..." value="{{ $query ?? '' }}">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Add New Client --}}
                    <div class="col-md-4 text-md-end">
                        <a href="{{ route('admin.clients.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-1"></i> Add New Client
                        </a>
                    </div>
                </div>
            </div>

            {{-- Body Section --}}
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Balance</th>
                            <th>Status</th>
                            <th>Registered Via</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ $client->phone }}</td>
                                <td>{{ $client->address }}</td>
                                <td>{{ $client->balance }}</td>
                                <td>
                                    <span class="badge {{ $client->status->value == \App\Enums\ClientStatusEnum::active->value ? 'bg-success' : 'bg-secondary' }}">
                                        {{ \App\Enums\ClientStatusEnum::labels()[$client->status->value] ?? 'Unknown' }}
                                    </span>
                                </td>
                                <td>{{ $client->registered_via }}</td>
                                <td>
                                    <a href="{{ route('admin.clients.edit', $client->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="{{ route('admin.clients.show', $client->id) }}"
                                         class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <form action="{{ route('admin.clients.destroy', $client->id) }}"
                                         method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" type="submit"
                                                onclick="return confirm('Are you sure you want to delete this client?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">No Clients Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="card-footer clearfix">
                {{ $clients->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
