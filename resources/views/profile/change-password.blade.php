@extends('layouts.sapta')

@section('title', 'Change Password')

@push('styles')
<style>
    .cp-container {
        max-width: 600px;
        margin: 0 auto;
    }
    .cp-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .cp-header {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        padding: 32px;
        color: #fff;
    }
    .cp-header-icon {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
    }
    .cp-header h1 {
        font-size: 24px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }
    .cp-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 14px;
    }
    .cp-body {
        padding: 32px;
    }
    .cp-field {
        margin-bottom: 24px;
    }
    .cp-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .cp-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }
    .cp-input {
        width: 100%;
        padding: 14px 48px 14px 44px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 15px;
        transition: all 0.2s;
        background: #f8fafc;
        color: #0f172a;
    }
    .cp-input:focus {
        outline: none;
        border-color: #2563eb;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    .cp-input.is-invalid {
        border-color: #dc2626;
        background: #fef2f2;
    }
    .cp-input-icon {
        position: absolute;
        left: 14px;
        color: #94a3b8;
        font-size: 14px;
        pointer-events: none;
    }
    .cp-toggle {
        position: absolute;
        right: 12px;
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 8px;
        border-radius: 6px;
        transition: all 0.2s;
        font-size: 14px;
    }
    .cp-toggle:hover {
        background: #f1f5f9;
        color: #2563eb;
    }
    .cp-hint {
        font-size: 12px;
        color: #64748b;
        margin-top: 6px;
    }
    .cp-error {
        font-size: 13px;
        color: #dc2626;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .cp-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e2e8f0;
    }
    .cp-btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .cp-btn-primary {
        background: #2563eb;
        color: #fff;
        flex: 1;
        justify-content: center;
    }
    .cp-btn-primary:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }
    .cp-btn-secondary {
        background: #f1f5f9;
        color: #475569;
    }
    .cp-btn-secondary:hover {
        background: #e2e8f0;
    }
    .cp-strength {
        margin-top: 8px;
        display: flex;
        gap: 4px;
    }
    .cp-strength-bar {
        flex: 1;
        height: 4px;
        background: #e2e8f0;
        border-radius: 2px;
        transition: all 0.3s;
    }
    .cp-strength-bar.active { background: #dc2626; }
    .cp-strength-bar.medium { background: #f59e0b; }
    .cp-strength-bar.strong { background: #16a34a; }
    .cp-strength-text {
        font-size: 11px;
        color: #64748b;
        margin-top: 4px;
    }
</style>
@endpush

@section('content')
<div class="cp-container">

    @if(session('success'))
        <div style="background: #f0fdf4; border: 1px solid #86efac; color: #166534; padding: 12px 16px; border-radius: 10px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-check-circle"></i>
            <strong>{{ session('success') }}</strong>
        </div>
    @endif

    <div class="cp-card">
        <div class="cp-header">
            <div class="cp-header-icon">
                <i class="fas fa-shield-halved"></i>
            </div>
            <h1>Change Password</h1>
            <p>Badilisha password yako kwa usalama</p>
        </div>

        <div class="cp-body">
            <form method="POST" action="{{ route('profile.update-password') }}" id="changePasswordForm">
                @csrf
                @method('PUT')

                <!-- CURRENT PASSWORD -->
                <div class="cp-field">
                    <label class="cp-label" for="current_password">Current Password</label>
                    <div class="cp-input-wrap">
                        <i class="fas fa-lock cp-input-icon"></i>
                        <input type="password"
                               name="current_password"
                               id="current_password"
                               class="cp-input @error('current_password') is-invalid @enderror"
                               placeholder="Weka password yako ya sasa"
                               autocomplete="current-password"
                               required>
                        <button type="button" class="cp-toggle" onclick="togglePassword('current_password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <div class="cp-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- NEW PASSWORD -->
                <div class="cp-field">
                    <label class="cp-label" for="password">New Password</label>
                    <div class="cp-input-wrap">
                        <i class="fas fa-key cp-input-icon"></i>
                        <input type="password"
                               name="password"
                               id="password"
                               class="cp-input @error('password') is-invalid @enderror"
                               placeholder="Weka password mpya"
                               autocomplete="new-password"
                               required
                               oninput="checkStrength(this.value)">
                        <button type="button" class="cp-toggle" onclick="togglePassword('password', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="cp-strength">
                        <div class="cp-strength-bar" id="bar1"></div>
                        <div class="cp-strength-bar" id="bar2"></div>
                        <div class="cp-strength-bar" id="bar3"></div>
                        <div class="cp-strength-bar" id="bar4"></div>
                    </div>
                    <div class="cp-strength-text" id="strengthText">Password lazima iwe na herufi 8 au zaidi</div>
                    @error('password')
                        <div class="cp-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                    @enderror
                </div>

                <!-- CONFIRM PASSWORD -->
                <div class="cp-field">
                    <label class="cp-label" for="password_confirmation">Confirm New Password</label>
                    <div class="cp-input-wrap">
                        <i class="fas fa-key cp-input-icon"></i>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               class="cp-input"
                               placeholder="Rudia password mpya"
                               autocomplete="new-password"
                               required>
                        <button type="button" class="cp-toggle" onclick="togglePassword('password_confirmation', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="cp-actions">
                    <button type="submit" class="cp-btn cp-btn-primary">
                        <i class="fas fa-save"></i> Save Password
                    </button>
                    <a href="{{ route('profile.show') }}" class="cp-btn cp-btn-secondary">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection



@push('scripts')
<script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon = btn.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    function checkStrength(password) {
        const bars = [
            document.getElementById('bar1'),
            document.getElementById('bar2'),
            document.getElementById('bar3'),
            document.getElementById('bar4')
        ];
        const text = document.getElementById('strengthText');

        bars.forEach(b => {
            b.classList.remove('active', 'medium', 'strong');
        });

        if (!password) {
            text.textContent = 'Password lazima iwe na herufi 8 au zaidi';
            return;
        }

        let score = 0;
        if (password.length >= 8) score++;
        if (password.length >= 12) score++;
        if (/[A-Z]/.test(password) && /[a-z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^A-Za-z0-9]/.test(password)) score++;

        let level = 0;
        let label = '';
        let cls = 'active';

        if (score <= 2) {
            level = 1;
            label = 'Dhaifu';
            cls = 'active';
        } else if (score <= 3) {
            level = 2;
            label = 'Wastani';
            cls = 'medium';
        } else if (score <= 4) {
            level = 3;
            label = 'Nzuri';
            cls = 'strong';
        } else {
            level = 4;
            label = 'Imara sana';
            cls = 'strong';
        }

        for (let i = 0; i < level; i++) {
            bars[i].classList.add(cls);
        }

        text.textContent = 'Nguvu ya password: ' + label;
    }
</script>
@endpush