@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3>Edit User</h3>
        </div>

        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $user->name ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $user->email ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label>Role</label>
                    <select name="role_id" class="form-select" required>
                        <option value="1" {{ $user->role_id == 1 ? 'selected' : '' }}>Super Admin</option>
                        <option value="2" {{ $user->role_id == 2 ? 'selected' : '' }}>Management</option>
                        <option value="3" {{ $user->role_id == 3 ? 'selected' : '' }}>Employee</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Update User</button>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
