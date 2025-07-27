@extends('layouts.admin')

@section('content')
<form method="GET" action="{{ route('leave.manage') }}" class="mb-3" style="margin-bottom: 30px;">
  <div class="row">
    <div class="col-md-3">
      <input type="text" name="name" class="form-control" placeholder="Search Name" value="{{ request('name') }}">
    </div>
    <div class="col-md-3">
      <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
    </div>
    <div class="col-md-3">
      <select name="status" class="form-control">
        <option value="">All Status</option>
        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
        <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
        <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
      </select>
    </div>
    <div class="col-md-3">
      <button type="submit" class="btn btn-primary">Search</button>
      <button type="button" class="btn btn-secondary" onclick="clearFilters()">Clear</button>

    </div>
  </div>
</form>

<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Type</th>
            <th>Dates</th>
            <th>Days</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Rejection Reason</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($leaves as $index => $leave)
            <tr @if(request('highlight') == $leave->id) class="table-warning" id="highlight-leave" @endif>
                <td>{{ $index + 1 }}</td>
                <td>{{ $leave->user->name ?? 'Unknown' }}</td>
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
                <td>{{ $leave->reason }}</td>
                <td>
                    @if($leave->status === 'Approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($leave->status === 'Rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending</span>
                    @endif
                </td>
                <td class="rejection-reason-cell">{{ $leave->rejection_reason ?? '-' }}</td>
                <td>
                    @if($leave->status === 'Pending')
                        <form action="{{ route('leave.approve', $leave->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success">Approve</button>
                        </form>

                        <!-- Reject Trigger -->
                        <button class="btn btn-danger" data-toggle="modal" data-target="#rejectModal-{{ $leave->id }}">
                            Reject
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="rejectModal-{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel-{{ $leave->id }}" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <form method="POST" action="{{ route('leave.reject') }}">
                                @csrf
                                <input type="hidden" name="leave_id" value="{{ $leave->id }}">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="rejectModalLabel-{{ $leave->id }}">Reject Leave Request</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <textarea name="rejection_reason" class="form-control" rows="4"></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Reject</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                          </div>
                        </div>                    
                        @else
                        <span class="text-muted">No Action</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection


<script>
function clearFilters() {
    window.location.href = "{{ route('leave.manage') }}";
}
</script>

<script>
    window.onload = function () {
        const highlighted = document.getElementById('highlight-leave');
        if (highlighted) {
            highlighted.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    };
</script>
