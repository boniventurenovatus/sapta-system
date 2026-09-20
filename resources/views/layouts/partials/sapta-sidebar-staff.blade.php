{{-- STAFF SIDEBAR --}}
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

    {{-- MY HR --}}
    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">MY HR</div>
        <a href="{{ url('/attendances') }}" class="sapta-nav-item {{ request()->is('attendances*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-clock"></i></span>
            <span class="sapta-nav-text">My Attendance</span>
        </a>
        <a href="{{ url('/leave-requests') }}" class="sapta-nav-item {{ request()->is('leave-requests*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-calendar-check"></i></span>
            <span class="sapta-nav-text">My Leave Requests</span>
        </a>
        <a href="{{ route('my-payslips') }}" class="sapta-nav-item {{ request()->is('my-payslips*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-money-bill-wave"></i></span>
            <span class="sapta-nav-text">My Payslips</span>
        </a>
    </div>

    {{-- COMMUNICATION --}}
    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">COMMUNICATION</div>
        <a href="{{ route('communication.inbox') }}" class="sapta-nav-item {{ request()->routeIs('communication.inbox') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-inbox"></i></span>
            <span class="sapta-nav-text">Inbox</span>
        </a>
        <a href="{{ route('communication.announcements') }}" class="sapta-nav-item {{ request()->routeIs('communication.announcements') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-bullhorn"></i></span>
            <span class="sapta-nav-text">Announcements</span>
        </a>
    </div>

    {{-- OTHERS --}}
    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">OTHERS</div>
        <a href="{{ route('notifications.index') }}" class="sapta-nav-item {{ request()->is('notifications*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-bell"></i></span>
            <span class="sapta-nav-text">Notifications</span>
            @auth
                @if(\Schema::hasTable('notifications') && \Schema::hasTable('notifications') && \Schema::hasTable('notifications') && \Schema::hasTable('notifications') && auth()->user()->unreadNotifications->count() > 0)
                    <span class="sapta-nav-badge">{{ \Schema::hasTable('notifications') ? auth()->user()->unreadNotifications->count() : 0 }}</span>
                @endif
            @endauth
        </a>
    </div>
</nav>