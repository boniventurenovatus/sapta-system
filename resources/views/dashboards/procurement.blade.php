@extends('layouts.app')
@section('title', 'Procurement Dashboard')
@section('content')
<div class="container py-4">
    <h1>Procurement Dashboard</h1>
    <p class="lead">Karibu, {{ auth()->user()->username }}</p>
</div>
@endsection