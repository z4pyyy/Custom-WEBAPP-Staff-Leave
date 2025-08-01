@extends('layouts.admin')

@section('content')
<h2>Manage Leave</h2>
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
        <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
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
            <th>Attachment</th>
        </tr>
    </thead>
    <tbody>
        @foreach($leaves as $index => $leave)
            <tr @if(request('highlight') == $leave->id) class="table-warning" id="highlight-leave" @endif>
                <td>{{ $index + 1 }}</td>
                <td>{{ $leave->user->name ?? 'Unknown' }}</td>
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
                <td>{{ $leave->reason }}</td>
                <td>
                    @if($leave->status === 'Approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($leave->status === 'Rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @elseif($leave->status === 'Cancelled')
                        <span class="badge bg-secondary">Cancelled</span>
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
                        @if(in_array(auth()->user()->role_id, [1, 2]) && $leave->medical_certificate)
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
