@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">System Settings</h1>

    {{-- Flash message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Bootstrap Nav Tabs --}}
    <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="leave-type-tab" data-toggle="tab" href="#leave-type" role="tab">Leave Type</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="annual-rule-tab" data-toggle="tab" href="#annual-rule" role="tab">Annual Leave Rule</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="system-info-tab" data-toggle="tab" href="#system-info" role="tab">System Info</a>
        </li>
    </ul>

    {{-- Tab Content --}}
    <div class="tab-content" id="settingsTabsContent">
        {{-- Leave Type Tab --}}
        <div class="tab-pane fade show active" id="leave-type" role="tabpanel">
            @include('system.partials.leave_type')
        </div>

        {{-- Annual Leave Rule Tab --}}
        <div class="tab-pane fade" id="annual-rule" role="tabpanel">
            @include('system.partials.annual_rule')
        </div>

        {{-- System Info Tab --}}
        <div class="tab-pane fade" id="system-info" role="tabpanel">
            @include('system.partials.system_info')
        </div>
    </div>
</div>
@endsection
