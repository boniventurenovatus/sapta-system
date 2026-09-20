{{-- MEAL SIDEBAR --}}
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

    <div class="sapta-nav-section">
        <div class="sapta-nav-section-title">MEAL</div>
        <a href="{{ url('/projects') }}" class="sapta-nav-item {{ request()->is('projects*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-folder-open"></i></span>
            <span class="sapta-nav-text">Projects</span>
        </a>
        <a href="{{ route('documents.index') }}" class="sapta-nav-item {{ request()->is('documents*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-file-lines"></i></span>
            <span class="sapta-nav-text">Documents</span>
        </a>
        <a href="{{ route('trainings.index') }}" class="sapta-nav-item {{ request()->is('trainings*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-graduation-cap"></i></span>
            <span class="sapta-nav-text">Trainings</span>
        </a>
        <a href="{{ route('performance-reviews.index') }}" class="sapta-nav-item {{ request()->is('performance-reviews*') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-star"></i></span>
            <span class="sapta-nav-text">Performance Reviews</span>
        </a>
    </div>

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
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="sapta-nav-badge">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            @endauth
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
        <a href="{{ route('communication.announcements') }}" class="sapta-nav-item {{ request()->routeIs('communication.announcements') ? 'active' : '' }}">
            <span class="sapta-nav-icon"><i class="fas fa-bullhorn"></i></span>
            <span class="sapta-nav-text">Announcements</span>
        </a>
    </div></nav>