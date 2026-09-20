<aside class="sapta-sidebar" id="saptaSidebar">

    <div class="sapta-sidebar-header">
        <a href="{{ url('/dashboard') }}" class="sapta-brand">
            <span class="sapta-brand-icon"><i class="fas fa-building"></i></span>
            <span class="sapta-brand-text">
                <strong>SAPTA</strong>
                <small>Management System</small>
            </span>
        </a>
    </div>

    <nav class="sapta-sidebar-nav">

        {{-- MAIN --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">MAIN</div>
            <a href="{{ url('/dashboard') }}" class="sapta-nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-gauge-high"></i></span>
                <span class="sapta-nav-text">Dashboard</span>
            </a>
        </div>

        {{-- MANAGEMENT --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">MANAGEMENT</div>

            <a href="{{ url('/organizations') }}" class="sapta-nav-item {{ request()->is('organizations*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-building"></i></span>
                <span class="sapta-nav-text">Organizations</span>
            </a>

            <a href="{{ route('departments.index') }}" class="sapta-nav-item {{ request()->is('departments*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-sitemap"></i></span>
                <span class="sapta-nav-text">Departments</span>
            </a>

            <a href="{{ url('/organogram') }}" class="sapta-nav-item {{ request()->is('organogram*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-network-wired"></i></span>
                <span class="sapta-nav-text">Organogram</span>
            </a>

            <a href="{{ route('positions.index') }}" class="sapta-nav-item {{ request()->is('positions*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-briefcase"></i></span>
                <span class="sapta-nav-text">Positions</span>
            </a>

            <a href="{{ route('employee-positions.index') }}" class="sapta-nav-item {{ request()->is('employee-positions*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-id-badge"></i></span>
                <span class="sapta-nav-text">Employee Positions</span>
            </a>

            <a href="{{ url('/employees') }}" class="sapta-nav-item {{ request()->is('employees*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-users"></i></span>
                <span class="sapta-nav-text">Employees</span>
            </a>

            <a href="{{ url('/projects') }}" class="sapta-nav-item {{ request()->is('projects*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-folder-open"></i></span>
                <span class="sapta-nav-text">Projects</span>
            </a>
            <a href="{{ route('documents.index') }}" class="sapta-nav-item {{ request()->is('documents*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-file-lines"></i></span>
                <span class="sapta-nav-text">Documents</span>
            </a>

            <a href="{{ route('tasks.index') }}" class="sapta-nav-item {{ request()->is('tasks*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-list-check"></i></span>
                <span class="sapta-nav-text">Tasks</span>
            </a>
        </div>

        {{-- TIME & LEAVE --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">TIME & LEAVE</div>
            <a href="{{ url('/attendances') }}" class="sapta-nav-item {{ request()->is('attendances*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-clock"></i></span>
                <span class="sapta-nav-text">Attendance</span>
            </a>
            <a href="{{ url('/leave-requests') }}" class="sapta-nav-item {{ request()->is('leave-requests*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-calendar-check"></i></span>
                <span class="sapta-nav-text">Leave Requests</span>
            </a>
            <a href="{{ route('performance-reviews.index') }}" class="sapta-nav-item {{ request()->is('performance-reviews*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-star"></i></span>
                <span class="sapta-nav-text">Performance Reviews</span>
            </a>
            <a href="{{ route('trainings.index') }}" class="sapta-nav-item {{ request()->is('trainings*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-graduation-cap"></i></span>
                <span class="sapta-nav-text">Trainings</span>
            </a>
            <a href="{{ route('recruitment.index') }}" class="sapta-nav-item {{ request()->is('recruitment*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-user-plus"></i></span>
                <span class="sapta-nav-text">Recruitment</span>
            </a>
        </div>

        {{-- FINANCE --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">FINANCE</div>
            <a href="{{ route('payroll.index') }}" class="sapta-nav-item {{ request()->is('payroll*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-money-bill-wave"></i></span>
                <span class="sapta-nav-text">Payroll</span>
            </a>
            <a href="{{ route('payment-vouchers.index') }}" class="sapta-nav-item {{ request()->is('payment-vouchers*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-file-invoice"></i></span>
                <span class="sapta-nav-text">Payment Vouchers</span>
            </a>
            <a href="{{ route('receipts.index') }}" class="sapta-nav-item {{ request()->is('receipts*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-receipt"></i></span>
                <span class="sapta-nav-text">Receipts</span>
            </a>
            <a href="{{ route('budgets.index') }}" class="sapta-nav-item {{ request()->is('budgets*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-chart-pie"></i></span>
                <span class="sapta-nav-text">Budgets</span>
            </a>
        </div>

        {{-- ADMINISTRATION --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">ADMINISTRATION</div>
            <a href="{{ url('/users') }}" class="sapta-nav-item {{ request()->is('users*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-user-shield"></i></span>
                <span class="sapta-nav-text">Users</span>
            </a>
            <a href="{{ url('/roles') }}" class="sapta-nav-item {{ request()->is('roles*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-user-tag"></i></span>
                <span class="sapta-nav-text">Roles</span>
            </a>
            <a href="{{ url('/permissions') }}" class="sapta-nav-item {{ request()->is('permissions*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-key"></i></span>
                <span class="sapta-nav-text">Permissions</span>
            </a>
            <a href="{{ route('notifications.index') }}" class="sapta-nav-item {{ request()->is('notifications*') ? 'active' : '' }}">
                <span class="sapta-nav-icon"><i class="fas fa-bell"></i></span>
                <span class="sapta-nav-text">Notifications</span>
                @auth
                    @auth @if(auth()->check()) @php $unread = auth()->user()->unreadNotifications->count(); @endphp @else @php $unread = 0; @endphp @endif @endauth
                    @if($unread > 0)
                        <span style="margin-left:auto; background:#dc2626; color:#fff; font-size:0.65rem; font-weight:800; padding:0.15rem 0.45rem; border-radius:999px;">{{ $unread }}</span>
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













