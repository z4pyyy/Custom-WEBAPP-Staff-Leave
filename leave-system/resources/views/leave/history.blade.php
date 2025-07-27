@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Leave History</h2>

    <form method="GET" action="{{ route('leave.history') }}" class="mb-3 d-flex gap-2">
        <select name="year" class="form-control filter-select">
            <option value="">All Years</option>
            @foreach(range(now()->year, 2020) as $year)
                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>

        <select name="month" class="form-control filter-select">
            <option value="">All Months</option>
            @foreach(range(1, 12) as $month)
                <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                    {{ \Carbon\Carbon::create()->month($month)->format('F') }}
                </option>
            @endforeach
        </select>

        <select name="status" class="form-control filter-select">
            <option value="">All Status</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>

        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('leave.history') }}" class="btn btn-secondary">Clear</a>
    </form>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>Dates</th>
                <th>Days</th>
                <th>Status</th>
                @if(auth()->user()->role === 'admin')
                    <th>User</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($leaves as $index => $leave)
                <tr @if(request('highlight') == $leave->id) id="highlight-leave" class="table-warning" @endif>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $leave->type }}</td>
                    <td>{{ $leave->start_date }} ~ {{ $leave->end_date }}</td>
                    <td>
                    @php
                        $start = \Carbon\Carbon::parse($leave->start_date);
                        $end = \Carbon\Carbon::parse($leave->end_date);
                    @endphp

                    @if ($start->eq($end))
                        @php
                            $day = $leave->day_length;
                            $is_float = floor($day) != $day;
                        @endphp

                        @if ($is_float)
                            {{ $day }} day(s)
                        @elseif ($day == 1)
                            1 day
                        @else
                            {{ intval($day) }} days
                        @endif
                    @else
                        {{ $start->diffInDays($end) + 1 }} days
                    @endif
                </td>
                    <td>
                        @if($leave->status === 'Approved')
                            <span class="badge badge-success">Approved</span>
                        @elseif($leave->status === 'Rejected')
                            <span class="badge badge-danger">Rejected</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    @if(auth()->user()->role === 'admin')
                        <td>{{ $leave->user->name ?? 'Unknown' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        {{ $leaves->links() }} {{-- Laravel pagination --}}
    </div>
</div>
@endsection


@section('scripts')
<script>
    window.onload = function () {
        const highlighted = document.getElementById('highlight-leave');
        if (highlighted) {
            highlighted.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    };
</script>
@endsection
