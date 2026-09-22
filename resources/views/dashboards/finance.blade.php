@extends('layouts.app')
@section('title', 'Finance Dashboard')
@section('content')
<div class="container py-4">
    <h1>Finance Dashboard</h1>
    <div class="mt-4">
        <a href="/payroll" class="btn btn-primary">Payroll</a>
        <a href="/reports/payroll" class="btn btn-secondary">Reports</a>
    </div>
</div>
@endsection