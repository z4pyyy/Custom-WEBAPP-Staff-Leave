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
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                <th>Action</th>
                <th>Attachment</th>
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
<td>
    {{ \Carbon\Carbon::parse($leave->start_date)->format('Y-m-d') }} ~ {{ \Carbon\Carbon::parse($leave->end_date)->format('Y-m-d') }}

    @if(fmod(floatval($leave->day_length), 1) !== 0.0 && $leave->half_day_date && $leave->half_day_session)
        <br>
        <small class="text-muted">
            ({{ \Carbon\Carbon::parse($leave->half_day_date)->format('Y-m-d') }} - 
            {{ $leave->half_day_session === 'AM' ? 'Morning' : 'Afternoon' }})
        </small>
    @endif
</td>

                    <td>
                        {{ rtrim(rtrim($leave->day_length, '0'), '.') }} day{{ $leave->day_length == 1 ? '' : 's' }}
                    </td>
                    <td>
                        @if($leave->status === 'Approved')
                            <span class="badge badge-success">Approved</span>
                        @elseif($leave->status === 'Rejected')
                            <span class="badge badge-danger">Rejected</span>
                        @elseif($leave->status === 'Cancelled')
                            <span class="badge badge-secondary">Cancelled</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    @if(auth()->user()->role === 'admin')
                        <td>{{ $leave->user->name ?? 'Unknown' }}</td>
                    @endif
                    <td>
                    @php
                        $canCancel = in_array($leave->status, ['Pending', 'Approved'])
                            && !$leave->canceled_at
                            && !(\Carbon\Carbon::parse($leave->end_date)->lt(now()->subDays(7)) && $leave->status === 'Approved');
                    @endphp

                    @if($canCancel)
                        <form action="{{ route('leave.cancel', $leave->id) }}" method="POST" onsubmit="return confirm('Are you sure to cancel this leave?')">
                            @csrf
                            <button class="btn btn-danger btn-sm">Cancel</button>
                        </form>
                    @endif
                    </td>
                    <td>
                        @if(in_array(auth()->user()->role_id, [1, 2, 3]) && $leave->medical_certificate)
                            <a href="{{ asset('storage/' . $leave->medical_certificate) }}" target="_blank" class="btn btn-sm btn-info">
                                Preview
                            </a>
                            <a href="{{ asset('storage/' . $leave->medical_certificate) }}" download class="btn btn-sm btn-secondary">
                                Download
                            </a>
                        @else
                            @if($leave->type === 'Medical')
                                <span class="text-muted">No Access</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        @endif
                    </td>
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
