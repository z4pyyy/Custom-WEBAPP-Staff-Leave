@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-3">All Notifications</h3>
    <ul class="list-group">
        @forelse ($notifications as $notification)
            <li class="list-group-item">
                {{ $notification->data['message'] ?? 'No message' }}
                <span class="text-muted float-right">{{ $notification->created_at->diffForHumans() }}</span>
            </li>
        @empty
            <li class="list-group-item text-muted">No notifications found.</li>
        @endforelse
    </ul>
</div>
@endsection
