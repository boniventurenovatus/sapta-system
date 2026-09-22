@extends('layouts.app')
@section('title', 'Manager Dashboard')
@section('content')
<div class="container py-4">
    <h1>Manager Dashboard</h1>
    <div class="mt-4">
        <a href="/employees" class="btn btn-primary">Employees</a>
        <a href="/attendances" class="btn btn-secondary">Attendance</a>
        <a href="/reports/projects" class="btn btn-info">Reports</a>
    </div>
</div>
@endsection