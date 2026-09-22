@extends('layouts.app')
@section('title', 'Staff Dashboard')
@section('content')
<div class="container py-4">
    <h1>Staff Dashboard</h1>
    <p class="lead">Karibu, {{ auth()->user()->username }}</p>
    <div class="mt-4">
        <a href="/attendances" class="btn btn-primary">Attendance</a>
        <a href="/leave-requests" class="btn btn-secondary">Leave Requests</a>
    </div>
</div>
@endsection