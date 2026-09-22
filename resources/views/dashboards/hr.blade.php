@extends('layouts.app')
@section('title', 'HR Dashboard')
@section('content')
<div class="container py-4">
    <h1>HR Dashboard</h1>
    <div class="row mt-4">
        <div class="col-md-4"><div class="card p-3"><h5>Total Employees</h5><h2>{{ $totalEmployees ?? 0 }}</h2></div></div>
    </div>
    <div class="mt-4">
        <a href="/employees" class="btn btn-primary">Employees</a>
        <a href="/attendances" class="btn btn-secondary">Attendance</a>
        <a href="/leave-requests" class="btn btn-info">Leave Requests</a>
    </div>
</div>
@endsection