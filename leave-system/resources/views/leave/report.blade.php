@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Leave Report</h2>

    <!-- 筛选栏 -->
    <form method="GET" action="{{ route('leave.report') }}" class="print-filters">
        <div>
            <label for="employee">Employee</label>
            <select name="user_id" id="employee" class="form-select">
                <option value="">All</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="type">Leave Type</label>
            <select name="type" id="type" class="form-select">
                <option value="">All</option>
                <option value="Annual" {{ request('type') == 'Annual' ? 'selected' : '' }}>Annual</option>
                <option value="Medical" {{ request('type') == 'Medical' ? 'selected' : '' }}>Medical</option>
                <option value="Unpaid" {{ request('type') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="Replacement" {{ request('type') == 'Replacement' ? 'selected' : '' }}>Replacement</option>
            </select>
        </div>
        <div>
            <label for="month">Month</label>
            <select name="month" id="month" class="form-select">
                <option value="">All</option>
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="year">Year</label>
            <select name="year" id="year" class="form-select">
                <option value="">All</option>
                @for($y = 2020; $y <= date('Y') + 1; $y++)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-12 d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-primary">Filter</button>
            <button type="button" class="btn btn-secondary" onclick="clearFilters()">Clear</button>
            <button type="button" class="btn btn-success" onclick="window.print()">Print</button>
        </div>
    </form>

    <!-- 表格 -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Days</th>
                    <th>Reason</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                    <tr>
                        <td>{{ $leave->user->name }}</td>
                        <td>{{ $leave->type }}</td>
                        <td>{{ $leave->start_date }}</td>
                        <td>{{ $leave->end_date }}</td>
                        <td>{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays($leave->end_date) + 1 }}</td>
                        <td>{{ $leave->reason }}</td>
                        <td>{{ $leave->status }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No data found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection


<script>
function clearFilters() {
    window.location.href = "{{ route('leave.report') }}";
}
</script>