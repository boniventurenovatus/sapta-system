{{-- HR SIDEBAR --}}
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
    </div>

    {{-- TALENT --}}
    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">TALENT</div>
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
                @if(\Schema::hasTable('notifications') && \Schema::hasTable('notifications') && auth()->user()->unreadNotifications->count() > 0)
                    <span class="sapta-nav-badge">{{ \Schema::hasTable('notifications') ? auth()->user()->unreadNotifications->count() : 0 }}</span>
                @endif
            @endauth
        </a>
    </div>
</nav>