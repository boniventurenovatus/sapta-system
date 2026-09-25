@extends('layouts.sapta')

@section('title', 'Change Password')

@section('content')
<div class="tb-page-header">
    <h1 class="tb-page-title">
        <i class="fas fa-key"></i>
        Change Password
    </h1>
    <p class="tb-page-subtitle">Badilisha password yako kwa usalama.</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="tb-card" style="max-width: 600px;">
    <form method="POST" action="{{ route('profile.update-password') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password"
                   name="current_password"
                   id="current_password"
                   class="form-control @error('current_password') is-invalid @enderror"
                   required>
            @error('current_password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password"
                   name="password"
                   id="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required>
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
            <small class="form-text">Password lazima iwe na herufi 8 au zaidi.</small>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password"
                   name="password_confirmation"
                   id="password_confirmation"
                   class="form-control"
                   required>
        </div>

        <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Password
            </button>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>
@endsection