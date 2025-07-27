@extends('layouts.admin')

@section('content')
<h1 class="mb-4">Public Holiday Management</h1>

{{-- Success Message --}}
@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- Add Holiday --}}
<h5 class="mt-4">Add New Public Holiday</h5>
<hr>
<form method="POST" action="{{ route('public_holiday.store') }}" class="d-flex flex-wrap gap-2 mb-4">
    @csrf
    <input type="text" name="name" class="form-control" placeholder="Holiday Name" required style="width:200px;">
    <input type="date" name="date" class="form-control" required style="width:200px;">
    <button class="btn btn-success">Add Holiday</button>
</form>

{{-- Filter --}}
<h5 class="mt-4">Filter by Year</h5>
<hr>
<form method="GET" action="{{ route('public_holiday.index') }}" class="d-flex gap-2 mb-4">
    <select name="year" class="form-control" style="width:150px;">
        @foreach($years as $y)
            <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary">Filter</button>
</form>

{{-- Public Holiday List --}}
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Holiday Name</th>
            <th>Date</th>
            <th>Year</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($holidays as $index => $holiday)
        <tr>
            <td>{{ $holidays->firstItem() + $index }}</td>
            <td>
                <form method="POST" action="{{ route('public_holiday.update', $holiday->id) }}" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="name" value="{{ $holiday->name }}" class="form-control" style="width:450px;">
            </td>
            <td>
                    <input type="date" name="date" value="{{ $holiday->date }}" class="form-control" style="width:150px;">
            </td>
            <td>{{ $holiday->year }}</td>
            <td class="d-flex gap-1">
                    <button type="submit" class="btn btn-success btn-sm">Update</button>
                </form>
                <form method="POST" action="{{ route('public_holiday.destroy', $holiday->id) }}" onsubmit="return confirm('Confirm delete?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $holidays->withQueryString()->links() }}
@endsection
