@extends('layouts.admin')

@section('content')
    <h1 class="mb-4">Dashboard</h1>
    <div class="card p-3">
        <h4>Annual Leave Status ({{ now()->year }})</h4>
        <ul>
            <li><strong>Starting Balance:</strong> {{ $starting_balance }} days</li>
            <li><strong>Earned this year ({{ now()->month }} months):</strong> {{ $earned_this_year }} days</li>
            <li><strong>Used:</strong> {{ $annual_taken }} days</li>
            <li><strong>Remaining:</strong> <span class="text-success">{{ $annual_balance }} days</span></li>
        </ul>
    </div>
@endsection
