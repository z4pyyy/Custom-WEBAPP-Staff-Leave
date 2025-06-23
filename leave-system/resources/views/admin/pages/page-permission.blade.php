@extends('layouts.admin')

@section('content')
<style>
    .role-pill {
        display: inline-block;
        padding: 8px 18px;
        border-radius: 50px;
        background-color: #dee2e6;
        color: #333;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease;
        user-select: none;
        margin: 5px 10px 5px 0;
    }

    .role-toggle:checked + .role-pill {
        background-color: #0d6efd;
        color: #fff;
    }

    .role-pill:hover {
        background-color: #198754 !important;
        color: #fff;
    }

    .role-pill:active {
        transform: scale(0.97);
    }
</style>

<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Page Permissions</h3>
        </div>

        @if (session('success'))
            <div class="alert alert-success m-3">{{ session('success') }}</div>
        @endif

        <div class="card-body table-responsive p-0">
            <table class="table table-hover table-bordered table-striped text-nowrap">
                <thead class="table-dark">
                    <tr>
                        <th>Page Key</th>
                        <th>Description</th>
                        <th>Allowed Roles</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($permissions as $key => $item)
                    <tr>
                        <td><code>{{ $key }}</code></td>
                        <td>{{ $item['description'] ?? '-' }}</td>
                        <td>
                            <form action="{{ route('admin.page-permissions.update', $key) }}"
                                  method="POST"
                                  onsubmit="return true;">
                                @csrf
                                <div class="d-flex flex-wrap">
                                    @foreach($roles as $roleId => $roleName)
                                        <input type="checkbox" name="allowed_roles[]" value="{{ $roleId }}"
                                            id="{{ $key }}_role_{{ $roleId }}"
                                            class="d-none role-toggle"
                                            onchange="this.form.submit();"
                                            {{ in_array($roleId, $item['allowed_roles'] ?? []) ? 'checked' : '' }}>

                                        <label for="{{ $key }}_role_{{ $roleId }}" class="role-pill">
                                            {{ $roleName }}
                                        </label>
                                    @endforeach
                                </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted">No permissions found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
