{{-- ADMIN SIDEBAR --}}
<nav class="sapta-sidebar-nav">
    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">MAIN</div>
        <a href="{{ url('/dashboard') }}" class="sapta-nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-gauge-high"></i></span>
            <span class="sapta-nav-text">Dashboard</span>
        </a>
    </div>

    {{-- MY WORK --}}
    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">MY WORK</div>
        <a href="{{ route('my-work.index') }}" class="sapta-nav-item {{ request()->routeIs('my-work.index') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-briefcase"></i></span>
            <span class="sapta-nav-text">Overview</span>
        </a>
        <a href="{{ route('my-work.drafts') }}" class="sapta-nav-item {{ request()->routeIs('my-work.drafts') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-file-alt"></i></span>
            <span class="sapta-nav-text">My Drafts</span>
        </a>
        <a href="{{ route('my-work.tasks') }}" class="sapta-nav-item {{ request()->routeIs('my-work.tasks') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-tasks"></i></span>
            <span class="sapta-nav-text">My Tasks</span>
        </a>
        <a href="{{ route('my-work.approvals') }}" class="sapta-nav-item {{ request()->routeIs('my-work.approvals') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-check-circle"></i></span>
            <span class="sapta-nav-text">My Approvals</span>
        </a>
    </div>

    {{-- HUMAN RESOURCES --}}
    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">HUMAN RESOURCES</div>
        <a href="{{ url('/employees') }}" class="sapta-nav-item {{ request()->is('employees*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-users"></i></span>
            <span class="sapta-nav-text">Employees</span>
        </a>
        <a href="{{ route('departments.index') }}" class="sapta-nav-item {{ request()->is('departments*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-sitemap"></i></span>
            <span class="sapta-nav-text">Departments</span>
        </a>
        <a href="{{ route('positions.index') }}" class="sapta-nav-item {{ request()->is('positions*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-briefcase"></i></span>
            <span class="sapta-nav-text">Positions</span>
        </a>
        <a href="{{ url('/attendances') }}" class="sapta-nav-item {{ request()->is('attendances*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-clock"></i></span>
            <span class="sapta-nav-text">Attendance</span>
        </a>
        <a href="{{ url('/leave-requests') }}" class="sapta-nav-item {{ request()->is('leave-requests*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-calendar-check"></i></span>
            <span class="sapta-nav-text">Leave Requests</span>
        </a>
        <a href="{{ route('trainings.index') }}" class="sapta-nav-item {{ request()->is('trainings*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-graduation-cap"></i></span>
            <span class="sapta-nav-text">Trainings</span>
        </a>
        <a href="{{ route('recruitment.index') }}" class="sapta-nav-item {{ request()->is('recruitment*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-user-plus"></i></span>
            <span class="sapta-nav-text">Recruitment</span>
        </a>
        <a href="{{ route('performance-reviews.index') }}" class="sapta-nav-item {{ request()->is('performance-reviews*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-star"></i></span>
            <span class="sapta-nav-text">Performance Reviews</span>
        </a>
    </div>

    {{-- FINANCE --}}
    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">FINANCE</div>
        <a href="{{ route('budgets.index') }}" class="sapta-nav-item {{ request()->is('budgets*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-chart-pie"></i></span>
            <span class="sapta-nav-text">Budgets</span>
        </a>
        <a href="{{ route('receipts.index') }}" class="sapta-nav-item {{ request()->is('receipts*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-receipt"></i></span>
            <span class="sapta-nav-text">Receipts</span>
        </a>
        <a href="{{ route('payment-vouchers.index') }}" class="sapta-nav-item {{ request()->is('payment-vouchers*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-file-invoice"></i></span>
            <span class="sapta-nav-text">Payment Vouchers</span>
        </a>
        <a href="{{ route('payroll.index') }}" class="sapta-nav-item {{ request()->is('payroll*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-money-bill-wave"></i></span>
            <span class="sapta-nav-text">Payroll</span>
        </a>
    </div>

    {{-- PROCUREMENT --}}
    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">PROCUREMENT</div>
        <a href="{{ route('procurement.index') }}" class="sapta-nav-item {{ request()->routeIs('procurement.index') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-shopping-cart"></i></span>
            <span class="sapta-nav-text">Overview</span>
        </a>
        <a href="{{ route('procurement.requests') }}" class="sapta-nav-item {{ request()->routeIs('procurement.requests') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-clipboard-list"></i></span>
            <span class="sapta-nav-text">Requests</span>
        </a>
        <a href="{{ route('procurement.orders') }}" class="sapta-nav-item {{ request()->routeIs('procurement.orders') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-file-contract"></i></span>
            <span class="sapta-nav-text">Purchase Orders</span>
        </a>
        <a href="{{ route('procurement.suppliers') }}" class="sapta-nav-item {{ request()->routeIs('procurement.suppliers') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-truck"></i></span>
            <span class="sapta-nav-text">Suppliers</span>
        </a>
    </div>

    {{-- PROJECTS & PROGRAMS --}}
    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">PROJECTS & PROGRAMS</div>
        <a href="{{ url('/projects') }}" class="sapta-nav-item {{ request()->is('projects*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-folder-open"></i></span>
            <span class="sapta-nav-text">Projects</span>
        </a>
        <a href="{{ url('/tasks') }}" class="sapta-nav-item {{ request()->is('tasks*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-list-check"></i></span>
            <span class="sapta-nav-text">Tasks</span>
        </a>
        <a href="{{ route('documents.index') }}" class="sapta-nav-item {{ request()->is('documents*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-file-lines"></i></span>
            <span class="sapta-nav-text">Documents</span>
        </a>
    </div>

    {{-- COMMUNICATION --}}
    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">COMMUNICATION</div>
        <a href="{{ route('communication.inbox') }}" class="sapta-nav-item {{ request()->routeIs('communication.inbox') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-inbox"></i></span>
            <span class="sapta-nav-text">Inbox</span>
        </a>
        <a href="{{ route('communication.sent') }}" class="sapta-nav-item {{ request()->routeIs('communication.sent') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-paper-plane"></i></span>
            <span class="sapta-nav-text">Sent</span>
        </a>
        <a href="{{ route('communication.drafts') }}" class="sapta-nav-item {{ request()->routeIs('communication.drafts') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-file-alt"></i></span>
            <span class="sapta-nav-text">Drafts</span>
        </a>
        <a href="{{ route('communication.conversations') }}" class="sapta-nav-item {{ request()->routeIs('communication.conversations') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-comments"></i></span>
            <span class="sapta-nav-text">Conversations</span>
        </a>
        <a href="{{ route('communication.groups.index') }}" class="sapta-nav-item {{ request()->routeIs('communication.groups.index') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-users-rectangle"></i></span>
            <span class="sapta-nav-text">Groups</span>
        </a>
        <a href="{{ route('communication.announcements') }}" class="sapta-nav-item {{ request()->routeIs('communication.announcements') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-bullhorn"></i></span>
            <span class="sapta-nav-text">Announcements</span>
        </a>
        <a href="{{ route('communication.shared-files') }}" class="sapta-nav-item {{ request()->routeIs('communication.shared-files') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-folder-tree"></i></span>
            <span class="sapta-nav-text">Shared Files</span>
        </a>
    </div>

    {{-- REPORTS --}}
    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">REPORTS</div>
        <a href="{{ url('/reports') }}" class="sapta-nav-item {{ request()->is('reports*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-chart-column"></i></span>
            <span class="sapta-nav-text">Reports</span>
        </a>
        <a href="{{ route('notifications.index') }}" class="sapta-nav-item {{ request()->is('notifications*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-bell"></i></span>
            <span class="sapta-nav-text">Notifications</span>
            @auth
                @if(\Schema::hasTable('notifications') && \Schema::hasTable('notifications') && auth()->user()->unreadNotifications->count() > 0)
                    <span class="sapta-nav-badge">{{ \Schema::hasTable('notifications') ? auth()->user()->unreadNotifications->count() : 0 }}</span>
                @endif
            @endauth
        </a>
    </div>

    {{-- ADMINISTRATION --}}
    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">ADMINISTRATION</div>
        <a href="{{ route('users.index') }}" class="sapta-nav-item {{ request()->is('users*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-users-gear"></i></span>
            <span class="sapta-nav-text">Users</span>
        </a>
        <a href="{{ route('roles.index') }}" class="sapta-nav-item {{ request()->is('roles*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-user-shield"></i></span>
            <span class="sapta-nav-text">Roles</span>
        </a>
        <a href="{{ route('permissions.index') }}" class="sapta-nav-item {{ request()->is('permissions*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-key"></i></span>
            <span class="sapta-nav-text">Permissions</span>
        </a>
        <a href="{{ route('activity-logs.index') }}" class="sapta-nav-item {{ request()->is('activity-logs*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-history"></i></span>
            <span class="sapta-nav-text">Activity Logs</span>
        </a>
        <a href="{{ route('settings.index') }}" class="sapta-nav-item {{ request()->is('settings*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-cog"></i></span>
            <span class="sapta-nav-text">Settings</span>
        </a>
    </div>
</nav>