@extends('layouts.admin')

@section('content')
@vite(['resources/js/app.js', 'resources/css/app.css'])

<div class="container">
    <h2 class="calendar-title">Leave Calendar</h2>
    <div id="calendar"></div>

</div>


@endsection
