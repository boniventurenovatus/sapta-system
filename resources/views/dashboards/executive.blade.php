@extends('layouts.app')
@section('title', 'Executive Dashboard')
@section('content')
<div class="container py-4">
    <h1>Executive Dashboard</h1>
    <p class="lead">Karibu, {{ auth()->user()->username }}</p>
    <div class="mt-4">
        <a href="/reports/employees" class="btn btn-primary">Reports</a>
        <a href="/audit-logs" class="btn btn-secondary">Audit Logs</a>
    </div>
</div>
@endsection