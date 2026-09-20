{{-- FINANCE SIDEBAR --}}
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
                @if(\Schema::hasTable('notifications') && auth()->user()->unreadNotifications->count() > 0)
                    <span class="sapta-nav-badge">{{ \Schema::hasTable('notifications') ? auth()->user()->unreadNotifications->count() : 0 }}</span>
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