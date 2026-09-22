@extends('layouts.sapta')

@section('title', $title ?? 'Page')
@section('page-title', $title ?? 'Page')

@section('content')
<div style="max-width: 900px; margin: 0 auto; padding: 2rem;">
    <div style="background: #fff; border-radius: 16px; padding: 3rem; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <i class="fas fa-tools" style="font-size: 3rem; color: #94a3b8; margin-bottom: 1rem;"></i>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin-bottom: 0.5rem;">
            {{ $title ?? 'Page' }}
        </h1>
        <p style="color: #64748b;">
            This page is under construction. Coming soon.
        </p>
        <a href="{{ route('dashboard') }}" style="display: inline-block; margin-top: 1.5rem; padding: 0.75rem 1.5rem; background: #1a5276; color: #fff; border-radius: 8px; text-decoration: none; font-weight: 600;">
            ← Back to Dashboard
        </a>
    </div>
</div>
@endsection