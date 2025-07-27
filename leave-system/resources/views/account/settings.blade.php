@extends('layouts.admin')

@section('content')
<h1 class="mb-4">Account Settings</h1>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Profile Picture --}}
<div class="mb-3">
    @if ($user->avatar)
        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" width="100" class="mb-3 rounded-circle">
    @else
        <img src="https://via.placeholder.com/100?text=No+Avatar" alt="No Avatar" class="mb-3 rounded-circle">
    @endif
</div>

{{-- Update Personal Info --}}
<form method="POST" action="{{ route('account.settings') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>

    <div class="mb-3">
        <label for="department" class="form-label">Department</label>
        <select name="department" class="form-control">
            <option value="">-- Select Department --</option>
            @foreach(['Homs Living', 'Lavizo', 'Bedding & Co.'] as $dept)
                <option value="{{ $dept }}" {{ old('department', $user->department) == $dept ? 'selected' : '' }}>{{ $dept }}</option>
            @endforeach
        </select>
    </div>

    {{-- Upload Avatar --}}
    <div class="mb-3">
        <label for="avatar" class="form-label">Upload Avatar (Limited to 2mb)</label>
        <input type="file" name="avatar" class="form-control">
    </div>

    <div class="mb-3">
        <label for="birthday" class="form-label">Birthday</label>
        <input type="date" name="birthday" class="form-control" value="{{ old('birthday', $user->birthday) }}">
    </div>

    <div class="mb-3">
        <label for="gender" class="form-label">Gender</label>
        <select name="gender" class="form-control">
            <option value="">-- Select Gender --</option>
            @foreach(['Male', 'Female', 'Other'] as $g)
                <option value="{{ $g }}" {{ old('gender', $user->gender) == $g ? 'selected' : '' }}>{{ $g }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update Account</button>
</form>

<hr class="my-5">

{{-- Change Password --}}
<h5>Change Password</h5>
<form method="POST" action="{{ route('account.change-password') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">Current Password</label>
        <input type="password" name="current_password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="new_password" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input type="password" name="new_password_confirmation" class="form-control" required>
    </div>

    <button class="btn btn-warning">Change Password</button>
</form>
@endsection
