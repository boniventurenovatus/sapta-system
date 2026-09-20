<aside class="sapta-sidebar" id="saptaSidebar">

    <div class="sapta-sidebar-header">
        <a href="{{ url('/dashboard') }}" class="sapta-brand">
            <img src="{{ asset('images/sapta-logo.png') }}?v={{ time() }}" alt="SAPTA" class="sapta-brand-logo">
            <span class="sapta-brand-text">
                <strong>SAPTA</strong>
                <small>Management System</small>
            </span>
        </a>
    </div>

    @php
    $user = auth()->user();
    $roleCodes = $user->roles->pluck('code')->toArray();

    $isStaff = in_array('staff', $roleCodes);
    $isManager = in_array('manager', $roleCodes);
    $isDirector = in_array('director', $roleCodes);
    $isAdmin = in_array('super_admin', $roleCodes) || in_array('admin', $roleCodes);
@endphp

@if($isStaff)
    @include('layouts.partials.sapta-sidebar-staff')
@elseif($isManager)
    @include('layouts.partials.sapta-sidebar-manager')
@elseif($isDirector)
    @include('layouts.partials.sapta-sidebar-director')
@else
    @include('layouts.partials.sapta-sidebar-admin')
@endif

    {{-- OLD NAV (kama ipo) --}}
    <nav class="sapta-sidebar-nav" style="display:none;">

        {{-- MAIN --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">MAIN</div>
            <a href="{{ url('/dashboard') }}" class="sapta-nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-gauge-high"></i></span>
                <span class="sapta-nav-text">Dashboard</span>
            </a>
        </div>

        {{-- COMPANY --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">COMPANY</div>

            <div class="sapta-nav-group">
                <button type="button" class="sapta-nav-item sapta-nav-group-toggle {{ request()->is('organizations*') || request()->is('organogram*') ? 'active' : '' }}" onclick="toggleNavGroup('company', event)">
                    <span class="sapta-nav-icon"><i class="fas fa-building"></i></span>
                    <span class="sapta-nav-text">Company</span>
                    <i class="fas fa-chevron-down sapta-nav-chevron"></i>
                </button>
                <div class="sapta-nav-group-items" id="nav-group-company">
                    <a href="{{ url('/organizations') }}" class="sapta-nav-subitem {{ request()->is('organizations*') ? 'active' : '' }}">
                        <i class="fas fa-building"></i> Organizations
                    </a>
                    <a href="{{ url('/organogram') }}" class="sapta-nav-subitem {{ request()->is('organogram*') ? 'active' : '' }}">
                        <i class="fas fa-network-wired"></i> Organogram
                    </a>
                </div>
            </div>
        </div>

        {{-- HR --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">HR</div>

            <div class="sapta-nav-group">
                <button type="button" class="sapta-nav-item sapta-nav-group-toggle {{ request()->is('employees*') || request()->is('departments*') || request()->is('positions*') || request()->is('employee-positions*') ? 'active' : '' }}" onclick="toggleNavGroup('hr', event)">
                    <span class="sapta-nav-icon"><i class="fas fa-users"></i></span>
                    <span class="sapta-nav-text">Human Resources</span>
                    <i class="fas fa-chevron-down sapta-nav-chevron"></i>
                </button>
                <div class="sapta-nav-group-items" id="nav-group-hr">
                    <a href="{{ url('/employees') }}" class="sapta-nav-subitem {{ request()->is('employees*') ? 'active' : '' }}">
                        <i class="fas fa-user"></i> Employees
                    </a>
                    <a href="{{ route('departments.index') }}" class="sapta-nav-subitem {{ request()->is('departments*') ? 'active' : '' }}">
                        <i class="fas fa-sitemap"></i> Departments
                    </a>
                    <a href="{{ route('positions.index') }}" class="sapta-nav-subitem {{ request()->is('positions*') ? 'active' : '' }}">
                        <i class="fas fa-briefcase"></i> Positions
                    </a>
                    <a href="{{ route('employee-positions.index') }}" class="sapta-nav-subitem {{ request()->is('employee-positions*') ? 'active' : '' }}">
                        <i class="fas fa-id-badge"></i> Employee Positions
                    </a>
                </div>
            </div>

            <div class="sapta-nav-group">
                <button type="button" class="sapta-nav-item sapta-nav-group-toggle {{ request()->is('attendances*') || request()->is('leave-requests*') || request()->is('performance-reviews*') ? 'active' : '' }}" onclick="toggleNavGroup('time', event)">
                    <span class="sapta-nav-icon"><i class="fas fa-clock"></i></span>
                    <span class="sapta-nav-text">Time & Leave</span>
                    <i class="fas fa-chevron-down sapta-nav-chevron"></i>
                </button>
                <div class="sapta-nav-group-items" id="nav-group-time">
                    <a href="{{ url('/attendances') }}" class="sapta-nav-subitem {{ request()->is('attendances*') ? 'active' : '' }}">
                        <i class="fas fa-clock"></i> Attendance
                    </a>
                    <a href="{{ url('/leave-requests') }}" class="sapta-nav-subitem {{ request()->is('leave-requests*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i> Leave Requests
                    </a>
                    <a href="{{ route('performance-reviews.index') }}" class="sapta-nav-subitem {{ request()->is('performance-reviews*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i> Performance Reviews
                    </a>
                </div>
            </div>

            <div class="sapta-nav-group">
                <button type="button" class="sapta-nav-item sapta-nav-group-toggle {{ request()->is('trainings*') || request()->is('recruitment*') ? 'active' : '' }}" onclick="toggleNavGroup('talent', event)">
                    <span class="sapta-nav-icon"><i class="fas fa-graduation-cap"></i></span>
                    <span class="sapta-nav-text">Talent</span>
                    <i class="fas fa-chevron-down sapta-nav-chevron"></i>
                </button>
                <div class="sapta-nav-group-items" id="nav-group-talent">
                    <a href="{{ route('trainings.index') }}" class="sapta-nav-subitem {{ request()->is('trainings*') ? 'active' : '' }}">
                        <i class="fas fa-graduation-cap"></i> Trainings
                    </a>
                    <a href="{{ route('recruitment.index') }}" class="sapta-nav-subitem {{ request()->is('recruitment*') ? 'active' : '' }}">
                        <i class="fas fa-user-plus"></i> Recruitment
                    </a>
                </div>
            </div>
        </div>

        {{-- FINANCE --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">FINANCE</div>
            <div class="sapta-nav-group">
                <button type="button" class="sapta-nav-item sapta-nav-group-toggle {{ request()->is('payroll*') || request()->is('payment-vouchers*') || request()->is('receipts*') || request()->is('budgets*') ? 'active' : '' }}" onclick="toggleNavGroup('finance', event)">
                    <span class="sapta-nav-icon"><i class="fas fa-money-bill-wave"></i></span>
                    <span class="sapta-nav-text">Finance</span>
                    <i class="fas fa-chevron-down sapta-nav-chevron"></i>
                </button>
                <div class="sapta-nav-group-items" id="nav-group-finance">
                    <a href="{{ route('payroll.index') }}" class="sapta-nav-subitem {{ request()->is('payroll*') ? 'active' : '' }}">
                        <i class="fas fa-money-bill-wave"></i> Payroll
                    </a>
                    <a href="{{ route('payment-vouchers.index') }}" class="sapta-nav-subitem {{ request()->is('payment-vouchers*') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice"></i> Payment Vouchers
                    </a>
                    <a href="{{ route('receipts.index') }}" class="sapta-nav-subitem {{ request()->is('receipts*') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i> Receipts
                    </a>
                    <a href="{{ route('budgets.index') }}" class="sapta-nav-subitem {{ request()->is('budgets*') ? 'active' : '' }}">
                        <i class="fas fa-chart-pie"></i> Budgets
                    </a>
                </div>
            </div>
        </div>

        {{-- OPERATIONS --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">OPERATIONS</div>
            <div class="sapta-nav-group">
                <button type="button" class="sapta-nav-item sapta-nav-group-toggle {{ request()->is('projects*') || request()->is('tasks*') || request()->is('documents*') ? 'active' : '' }}" onclick="toggleNavGroup('ops', event)">
                    <span class="sapta-nav-icon"><i class="fas fa-folder-open"></i></span>
                    <span class="sapta-nav-text">Operations</span>
                    <i class="fas fa-chevron-down sapta-nav-chevron"></i>
                </button>
                <div class="sapta-nav-group-items" id="nav-group-ops">
                    <a href="{{ url('/projects') }}" class="sapta-nav-subitem {{ request()->is('projects*') ? 'active' : '' }}">
                        <i class="fas fa-folder-open"></i> Projects
                    </a>
                    <a href="{{ route('tasks.index') }}" class="sapta-nav-subitem {{ request()->is('tasks*') ? 'active' : '' }}">
                        <i class="fas fa-list-check"></i> Tasks
                    </a>
                    <a href="{{ route('documents.index') }}" class="sapta-nav-subitem {{ request()->is('documents*') ? 'active' : '' }}">
                        <i class="fas fa-file-lines"></i> Documents
                    </a>
                </div>
            </div>
        </div>

        {{-- ADMINISTRATION --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">ADMINISTRATION</div>

            <div class="sapta-nav-group">
                <button type="button" class="sapta-nav-item sapta-nav-group-toggle {{ request()->is('users*') || request()->is('roles*') || request()->is('permissions*') ? 'active' : '' }}" onclick="toggleNavGroup('admin', event)">
                    <span class="sapta-nav-icon"><i class="fas fa-user-shield"></i></span>
                    <span class="sapta-nav-text">Access Control</span>
                    <i class="fas fa-chevron-down sapta-nav-chevron"></i>
                </button>
                <div class="sapta-nav-group-items" id="nav-group-admin">
                    <a href="{{ url('/users') }}" class="sapta-nav-subitem {{ request()->is('users*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i> Users
                    </a>
                    <a href="{{ url('/roles') }}" class="sapta-nav-subitem {{ request()->is('roles*') ? 'active' : '' }}">
                        <i class="fas fa-user-tag"></i> Roles
                    </a>
                    <a href="{{ url('/permissions') }}" class="sapta-nav-subitem {{ request()->is('permissions*') ? 'active' : '' }}">
                        <i class="fas fa-key"></i> Permissions
                    </a>
            <a href="{{ url('/activity-logs') }}" class="sapta-nav-subitem {{ request()->is('activity-logs*') ? 'active' : '' }}">
                <i class="fas fa-history"></i> Activity Logs
            </a>
                </div>
            </div>

            <a href="{{ route('notifications.index') }}" class="sapta-nav-item {{ request()->is('notifications*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-bell"></i></span>
                <span class="sapta-nav-text">Notifications</span>
                @auth
                    @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                        <span class="sapta-nav-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                @endauth
            </a>

            <a href="{{ url('/reports') }}" class="sapta-nav-item {{ request()->is('reports*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-chart-column"></i></span>
                <span class="sapta-nav-text">Reports</span>
            </a>

            <a href="{{ route('audit-logs.index') }}" class="sapta-nav-item {{ request()->is('audit-logs*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-history"></i></span>
                <span class="sapta-nav-text">Audit Logs</span>
            </a>

            <a href="{{ url('/settings') }}" class="sapta-nav-item {{ request()->is('settings*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-gear"></i></span>
                <span class="sapta-nav-text">Settings</span>
            </a>
        </div>
    </nav>

    <div class="sapta-sidebar-footer">
        <div class="sapta-user-card">
            <div class="sapta-user-avatar"><i class="fas fa-user"></i></div>
            <div class="sapta-user-info">
                <strong>{{ auth()->user()->username ?? 'User' }}</strong>
                <small>{{ auth()->user()->role ?? 'User' }}</small>
            </div>
        </div>
        <form method="POST" action="{{ url('/logout') }}">
            @csrf
            <button type="submit" class="sapta-nav-item sapta-logout-button">
                <span class="sapta-nav-icon"><i class="fas fa-right-from-bracket"></i></span>
                <span class="sapta-nav-text">Logout</span>
            </button>
        </form>
    </div>
</aside>

@push('scripts')
<script>
function toggleNavGroup(id, event) {
    if (event) event.preventDefault();

    const group = document.getElementById('nav-group-' + id);
    const toggle = event ? event.currentTarget : null;
    if (!group) return;

    // Funga zote nyingine
    document.querySelectorAll('.sapta-nav-group-items').forEach(g => {
        if (g.id !== 'nav-group-' + id) {
            g.classList.remove('open');
            const parentToggle = g.previousElementSibling;
            if (parentToggle) parentToggle.classList.remove('open');
        }
    });

    // Toggle ya sasa
    group.classList.toggle('open');
    if (toggle) toggle.classList.toggle('open');

    // Save kwenye localStorage
    if (group.classList.contains('open')) {
        localStorage.setItem('sapta_nav_open', id);
    } else {
        localStorage.removeItem('sapta_nav_open');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.sapta-nav-group-items').forEach(group => {
        const hasActive = group.querySelector('.sapta-nav-subitem.active');
        if (hasActive) {
            group.classList.add('open');
            const toggle = group.previousElementSibling;
            if (toggle) toggle.classList.add('open');
        }
    });

    const lastOpen = localStorage.getItem('sapta_nav_open');
    if (lastOpen) {
        const group = document.getElementById('nav-group-' + lastOpen);
        if (group && !group.classList.contains('open')) {
            group.classList.add('open');
            const toggle = group.previousElementSibling;
            if (toggle) toggle.classList.add('open');
        }
    }
});
</script>
@endpush