@extends('layouts.admin')

@section('content')
    <h1>Leave Approval</h1>
    <p><strong>Leave ID:</strong> {{ $leave->id }}</p>
    <p><strong>Requested By:</strong> {{ $leave->user->name }}</p>
    <p><strong>Status:</strong> {{ $leave->status }}</p>

    {{-- 测试阶段可用 --}}

    <form method="POST" action="{{ url('/leave/approve/' . $leave->id) }}">
        @csrf
        <button type="submit" class="btn btn-success">Approve</button>
    </form>
@endsection
