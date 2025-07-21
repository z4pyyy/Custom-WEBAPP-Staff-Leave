@extends('layouts.admin')

@section('content')
<h1 class="mb-4">Annual Leave Balance Management</h1>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Add New Record Form --}}
<h5 class="mt-4">Add New Starting Balance</h5>
<p class="text-muted">Assign initial leave balance for employees (e.g. new joiners or late updates).</p>
<hr>
<form method="POST" action="{{ route('balance.store') }}">
    @csrf
    <div class="add-new-row">
        <div class="col">
            <select name="user_id" class="form-control" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col">
            <select name="year" class="form-control" required>
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="col">
            <input type="number" name="starting_balance" step="0.1" class="form-control" placeholder="Starting Balance" required>
        </div>
        <div class="col">
            <button type="submit" class="btn btn-success">Add New</button>
        </div>
    </div>
</form>

<h5 class="mt-4">Filter Existing Records</h5>
<p class="text-muted">View and update existing leave balances by year or name.</p>
<hr>
<div class="mb-3">
    <form method="GET" action="{{ route('balance.index') }}">
        <div class="filter-row">
            <div class="col-md-3">
                <select name="year" class="form-control">
                    <option value="">All Years</option>
                    @foreach($years as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="Search Name" value="{{ request('name') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('balance.index') }}" class="btn btn-secondary">Clear</a>
            </div>
        </div>
    </form>
</div>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Year</th>
            <th>Starting Balance</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($balances as $index => $balance)
        <tr>
            <td>{{ $balances->firstItem() + $index }}</td>
            <td>{{ $balance->user->name }}</td>
            <td>{{ $balance->year }}</td>
            <td>
                <form method="POST" action="{{ route('balance.update') }}" class="d-flex">
                    @csrf
                    <input type="hidden" name="balance_id" value="{{ $balance->id }}">
                    <input type="number" name="starting_balance" class="form-control" value="{{ $balance->starting_balance }}" step="0.01" style="width:100px;margin-right:10px;">
                    <button type="submit" class="btn btn-sm btn-success">Update</button>
                </form>
            </td>
            <td>
                <form action="{{ route('balance.destroy', $balance->id) }}" method="POST" onsubmit="return confirm('Confirm delete?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div>
    {{ $balances->withQueryString()->links() }}
</div>
@endsection

