@extends('layouts.app')
@section('title', 'Super Admin Dashboard')
@section('content')
<div class="container py-4">
    <h1>Super Admin Dashboard</h1>
    <div class="row mt-4">
        <div class="col-md-3"><div class="card p-3"><h5>Total Users</h5><h2>{{ $totalUsers ?? 0 }}</h2></div></div>
        <div class="col-md-3"><div class="card p-3"><h5>Total Employees</h5><h2>{{ $totalEmployees ?? 0 }}</h2></div></div>
        <div class="col-md-3"><div class="card p-3"><h5>Total Roles</h5><h2>{{ $totalRoles ?? 0 }}</h2></div></div>
        <div class="col-md-3"><div class="card p-3"><h5>Active Users</h5><h2>{{ $activeUsers ?? 0 }}</h2></div></div>
    </div>
    <div class="mt-4">
        <a href="/users" class="btn btn-primary">Users</a>
        <a href="/employees" class="btn btn-secondary">Employees</a>
        <a href="/roles" class="btn btn-info">Roles</a>
    </div>
</div>
@endsection