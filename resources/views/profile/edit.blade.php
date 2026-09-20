@extends('layouts.sapta')

@section('title', 'Edit Profile | SAPTA')

@section('content')
<style>
    .page-edit {
        max-width: 900px;
        margin: 0 auto;
    }
    .form-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #eef2f6;
        overflow: hidden;
    }
    .form-header {
        padding: 20px 24px;
        border-bottom: 1px solid #eef2f6;
        background: #fafbfc;
    }
    .form-header h1 {
        font-size: 20px;
        font-weight: 700;
        color: #0a1628;
    }
    .form-header p {
        font-size: 14px;
        color: #7a8a9e;
        margin-top: 2px;
    }
    .form-body {
        padding: 24px;
    }
    .form-group {
        margin-bottom: 18px;
    }
    .form-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #1a2a3a;
        margin-bottom: 5px;
    }
    .form-control {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1.5px solid #eef2f6;
        border-radius: 8px;
        font-size: 14px;
        color: #1a2a3a;
        background: #fafbfc;
        transition: 0.2s;
        outline: none;
    }
    .form-control:focus {
        border-color: #0a1628;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(10,22,40,0.06);
    }
    .form-control.error {
        border-color: #e74c3c;
    }
    .form-control::placeholder {
        color: #b0bccd;
    }
    .form-error {
        font-size: 12px;
        color: #e74c3c;
        margin-top: 4px;
    }
    .row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    @media (max-width: 768px) {
        .row-2 {
            grid-template-columns: 1fr;
        }
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 20px;
        border-top: 1px solid #eef2f6;
        margin-top: 8px;
    }
    .btn-save {
        padding: 10px 28px;
        background: #0a1628;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
    }
    .btn-save:hover {
        background: #1a2a3a;
        box-shadow: 0 4px 12px rgba(10,22,40,0.15);
    }
    .btn-cancel {
        padding: 10px 24px;
        background: transparent;
        color: #6a7a8e;
        font-size: 14px;
        font-weight: 500;
        border: 1.5px solid #eef2f6;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }
    .btn-cancel:hover {
        background: #f5f6f8;
        border-color: #d0d8e0;
        text-decoration: none;
        color: #6a7a8e;
    }
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #7a8a9e;
        margin-bottom: 20px;
    }
    .breadcrumb a {
        color: #4f8cf7;
        text-decoration: none;
    }
    .breadcrumb a:hover {
        text-decoration: underline;
    }
    .breadcrumb .sep {
        color: #d0d8e0;
    }
    .breadcrumb .current {
        color: #1a2a3a;
        font-weight: 500;
    }
</style>

<div class="page-edit">
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Dashboard</a>
        <span class="sep">/</span>
        <a href="{{ route('profile.show') }}">Profile</a>
        <span class="sep">/</span>
        <span class="current">{{ __("messages.edit") }}</span>
    </div>

    <div class="form-card">
        <div class="form-header">
            <h1>{{ __("messages.") }}Edit Profile</h1>
            <p>Update your account information</p>
        </div>

        <div class="form-body">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row-2">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}" class="form-control @error('username') error @enderror">
                        @error('username')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ __("messages.email") }}</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control @error('email') error @enderror">
                        @error('email')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row-2">
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') error @enderror" placeholder="Leave blank to keep current">
                        @error('password')
                            <div class="form-error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="profile_image" class="form-control @error('profile_image') error @enderror" accept="image/*">
                    <span class="text-xs text-slate-400">Max 2MB, JPG, PNG, or GIF</span>
                    @error('profile_image')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('profile.show') }}" class="btn-cancel">{{ __("messages.cancel") }}</a>
                    <button type="submit" class="btn-save">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


