@extends('layouts.admin') {{-- Use only the correct layout --}}

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">User Management</h3>
        </div>

        @if (session('success'))
            <div class="alert alert-success m-3">{{ session('success') }}</div>
        @endif

        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-striped text-nowrap">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($users as $id => $user)
                    <tr>
                        <td>{{ $id }}</td>
                        <td>{{ $user->name ?? 'N/A' }}</td>
                        <td>{{ $user->email ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-info">
                                {{ ucfirst($user->role ?? 'N/A') }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.users.edit', $id) }}"
                               class="btn btn-sm btn-warning" title="Edit User">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('admin.users.delete', $id) }}"
                                  method="POST" class="d-inline-block"
                                  onsubmit="return confirm('Delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Delete User">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">No users found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
