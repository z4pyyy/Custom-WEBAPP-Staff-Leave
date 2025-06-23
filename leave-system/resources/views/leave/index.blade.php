@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">My Leave Applications</h1>
    <div class="card">
        <div class="card-body">
            <ul>
                @forelse($leaves as $leave)
                    <li>{{ $leave->type }} from {{ $leave->start_date }} to {{ $leave->end_date }}</li>
                @empty
                    <li>No leave records found.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection
