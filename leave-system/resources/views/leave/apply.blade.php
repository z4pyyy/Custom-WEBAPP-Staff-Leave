@extends('layouts.admin')

@section('content')
<h1 class="mb-4">Apply for Leave</h1>

<form action="{{ route('leave.store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="type">Leave Type</label>
        <select name="type" id="type" class="form-control" required>
            <option value="">-- Select --</option>
            <option value="Annual">Annual</option>
            <option value="Medical">Medical</option>
            <option value="Unpaid">Unpaid</option>
            <option value="Replacement">Replacement</option>
        </select>
    </div>
    <div class="form-group">
        <label for="start_date">Start Date</label>
        <input type="date" name="start_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="end_date">End Date</label>
        <input type="date" name="end_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="day_length">Leave Days (Fillable 0.5, 1, 1.5)</label>
        <input type="number" name="day_length" class="form-control" step="0.5" min="0.5" value="1" required>
    </div>
    <div class="form-group">
        <label for="reason">Reason</label>
        <textarea name="reason" class="form-control" rows="3"></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection
