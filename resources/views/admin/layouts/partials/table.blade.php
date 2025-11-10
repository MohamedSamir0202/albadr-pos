<table class="table table-bordered table-striped">
    <thead>
    <tr>
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
        <tr>
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
                <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" class="d-inline-block">
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
            <td colspan="9" class="text-center">No Clients Found</td>
        </tr>
    @endforelse
    </tbody>
</table>
