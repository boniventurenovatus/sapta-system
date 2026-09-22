@php
    $user = auth()->user();
    $roleCodes = $user ? $user->roles->pluck('code')->toArray() : [];
    
    $hasAnyRole = function($roles) use ($roleCodes) {
        return !empty(array_intersect($roleCodes, $roles));
    };
    
    // Role Groups
    $isSuperAdmin = $hasAnyRole(['super_admin', 'admin']);
    $isExecutive = $hasAnyRole(['bod', 'ceo']);
    $isDirector = $hasAnyRole(['director', 'admin_director', 'program_director']);
    $isHR = $hasAnyRole(['hr_manager', 'hr_officer']);
    $isFinance = $hasAnyRole(['finance_manager', 'accountant', 'procurement_manager']);
    $isProgram = $hasAnyRole(['project_manager', 'project_officer', 'field_trainer', 'partnerships_manager']);
    $isMEAL = $hasAnyRole(['meal_manager', 'meal_officer', 'research_officer']);
    $isICT = $hasAnyRole(['ict_manager', 'community_manager']);
    $isManager = in_array('manager', $roleCodes);
    $isStaff = in_array('staff', $roleCodes);
    
    // Admin level — kwa menus za HR/Finance/Projects
    $isAdminLevel = $isSuperAdmin || $isExecutive || $isDirector;
    
    // Unread messages count
    $unreadCount = 0;
    try {
        $unreadCount = \App\Models\Message::where('recipient_id', auth()->id())->where('is_read', false)->count();
    } catch (\Exception $e) {}
@endphp

<aside class="sapta-sidebar" id="saptaSidebar">
    
    {{-- LOGO --}}
    <div class="sapta-sidebar-header">
        <a href="{{ route('dashboard') }}" class="sapta-logo">
            <img src="{{ asset('images/sapta-logo.png') }}" alt="SAPTA" style="height: 32px;">
            <div>
                <div class="sapta-logo-title">SAPTA</div>
                <div class="sapta-logo-subtitle">Management System</div>
            </div>
        </a>
    </div>

    {{-- NAVIGATION --}}
    <nav class="sapta-sidebar-nav">
        
        {{-- ============================================ --}}
        {{-- MAIN — Kwa wote --}}
        {{-- ============================================ --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">Main</div>
            
            <a href="{{ route('dashboard') }}" class="sapta-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge-high"></i>
                <span class="sapta-nav-text">Dashboard</span>
            </a>
        </div>

        {{-- ============================================ --}}
        {{-- SUPER ADMIN / ADMIN --}}
        {{-- ============================================ --}}
        @if($isSuperAdmin)
            <div class="sapta-nav-section">
                <div class="sapta-nav-section-title">Administration</div>
                
                <a href="{{ route('employees.index') }}" class="sapta-nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="sapta-nav-text">Employees</span>
                </a>
                
                <a href="{{ route('users.index') }}" class="sapta-nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-user-gear"></i>
                    <span class="sapta-nav-text">System Users</span>
                </a>
                
                <a href="{{ route('roles.index') }}" class="sapta-nav-item {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                    <i class="fas fa-shield-halved"></i>
                    <span class="sapta-nav-text">Roles</span>
                </a>
                
                <a href="{{ route('permissions.index') }}" class="sapta-nav-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                    <i class="fas fa-key"></i>
                    <span class="sapta-nav-text">Permissions</span>
                </a>
                
                <a href="{{ route('activity-logs.index') }}" class="sapta-nav-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                    <i class="fas fa-clock-rotate-left"></i>
                    <span class="sapta-nav-text">Activity Logs</span>
                </a>
                
                <a href="{{ route('settings.index') }}" class="sapta-nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <i class="fas fa-gear"></i>
                    <span class="sapta-nav-text">Settings</span>
                </a>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- EXECUTIVE (BOD, CEO) --}}
        {{-- ============================================ --}}
        @if($isExecutive)
            <div class="sapta-nav-section">
                <div class="sapta-nav-section-title">Executive</div>
                
                <a href="{{ route('employees.index') }}" class="sapta-nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="sapta-nav-text">Employees</span>
                </a>
                
                <a href="{{ route('reports.index') }}" class="sapta-nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="sapta-nav-text">Reports</span>
                </a>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- DIRECTOR --}}
        {{-- ============================================ --}}
        @if($isDirector)
            <div class="sapta-nav-section">
                <div class="sapta-nav-section-title">Management</div>
                
                <a href="{{ route('employees.index') }}" class="sapta-nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="sapta-nav-text">Employees</span>
                </a>
                
                <a href="{{ route('departments.index') }}" class="sapta-nav-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                    <i class="fas fa-sitemap"></i>
                    <span class="sapta-nav-text">Departments</span>
                </a>
                
                <a href="{{ route('reports.index') }}" class="sapta-nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="sapta-nav-text">Reports</span>
                </a>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- HR --}}
        {{-- ============================================ --}}
        @if($isHR || $isSuperAdmin)
            <div class="sapta-nav-section">
                <div class="sapta-nav-section-title">Human Resources</div>
                
                <a href="{{ route('employees.index') }}" class="sapta-nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="sapta-nav-text">Employees</span>
                </a>
                
                <a href="{{ route('departments.index') }}" class="sapta-nav-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                    <i class="fas fa-sitemap"></i>
                    <span class="sapta-nav-text">Departments</span>
                </a>
                
                <a href="{{ route('positions.index') }}" class="sapta-nav-item {{ request()->routeIs('positions.*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span class="sapta-nav-text">Positions</span>
                </a>
                
                <a href="{{ route('attendances.index') }}" class="sapta-nav-item {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span class="sapta-nav-text">Attendance</span>
                </a>
                
                <a href="{{ route('leave-requests.index') }}" class="sapta-nav-item {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span class="sapta-nav-text">Leave Requests</span>
                </a>
                
                <a href="{{ route('trainings.index') }}" class="sapta-nav-item {{ request()->routeIs('trainings.*') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span class="sapta-nav-text">Trainings</span>
                </a>
                
                <a href="{{ route('recruitment.index') }}" class="sapta-nav-item {{ request()->routeIs('recruitment.*') ? 'active' : '' }}">
                    <i class="fas fa-user-plus"></i>
                    <span class="sapta-nav-text">Recruitment</span>
                </a>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- FINANCE --}}
        {{-- ============================================ --}}
        @if($isFinance || $isSuperAdmin)
            <div class="sapta-nav-section">
                <div class="sapta-nav-section-title">Finance</div>
                
                <a href="{{ route('budgets.index') }}" class="sapta-nav-item {{ request()->routeIs('budgets.*') ? 'active' : '' }}">
                    <i class="fas fa-wallet"></i>
                    <span class="sapta-nav-text">Budgets</span>
                </a>
                
                <a href="{{ route('receipts.index') }}" class="sapta-nav-item {{ request()->routeIs('receipts.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span class="sapta-nav-text">Receipts</span>
                </a>
                
                <a href="{{ route('payment-vouchers.index') }}" class="sapta-nav-item {{ request()->routeIs('payment-vouchers.*') ? 'active' : '' }}">
                    <i class="fas fa-money-check"></i>
                    <span class="sapta-nav-text">Payment Vouchers</span>
                </a>
                
                <a href="{{ route('payroll.index') }}" class="sapta-nav-item {{ request()->routeIs('payroll.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span class="sapta-nav-text">Payroll</span>
                </a>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- PROJECTS & PROGRAMS --}}
        {{-- ============================================ --}}
        @if($isProgram || $isSuperAdmin)
            <div class="sapta-nav-section">
                <div class="sapta-nav-section-title">Projects & Programs</div>
                
                <a href="{{ route('projects.index') }}" class="sapta-nav-item {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                    <i class="fas fa-diagram-project"></i>
                    <span class="sapta-nav-text">Projects</span>
                </a>
                
                <a href="{{ route('tasks.index') }}" class="sapta-nav-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                    <i class="fas fa-list-check"></i>
                    <span class="sapta-nav-text">Tasks</span>
                </a>
                
                <a href="{{ route('documents.index') }}" class="sapta-nav-item {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                    <i class="fas fa-folder"></i>
                    <span class="sapta-nav-text">Documents</span>
                </a>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- MEAL --}}
        {{-- ============================================ --}}
        @if($isMEAL || $isSuperAdmin)
            <div class="sapta-nav-section">
                <div class="sapta-nav-section-title">MEAL</div>
                
                <a href="{{ route('reports.index') }}" class="sapta-nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    <span class="sapta-nav-text">Reports</span>
                </a>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- MANAGER — Team --}}
        {{-- ============================================ --}}
        @if($isManager)
            <div class="sapta-nav-section">
                <div class="sapta-nav-section-title">Management</div>
                
                <a href="{{ route('employees.index') }}" class="sapta-nav-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span class="sapta-nav-text">My Team</span>
                </a>
                
                <a href="{{ route('tasks.index') }}" class="sapta-nav-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                    <i class="fas fa-list-check"></i>
                    <span class="sapta-nav-text">Team Tasks</span>
                </a>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- STAFF — My Work --}}
        {{-- ============================================ --}}
        @if($isStaff)
            <div class="sapta-nav-section">
                <div class="sapta-nav-section-title">My Work</div>
                
                <a href="{{ route('tasks.index') }}" class="sapta-nav-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span class="sapta-nav-text">My Tasks</span>
                </a>
                
                <a href="{{ route('leave-requests.index') }}" class="sapta-nav-item {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i>
                    <span class="sapta-nav-text">My Leaves</span>
                </a>
                
                <a href="{{ route('attendances.index') }}" class="sapta-nav-item {{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i>
                    <span class="sapta-nav-text">My Attendance</span>
                </a>
                
                <a href="{{ route('trainings.index') }}" class="sapta-nav-item {{ request()->routeIs('trainings.*') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span class="sapta-nav-text">My Trainings</span>
                </a>
                
                <a href="{{ route('documents.index') }}" class="sapta-nav-item {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                    <i class="fas fa-folder"></i>
                    <span class="sapta-nav-text">My Documents</span>
                </a>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- COMMUNICATION — Kwa wote --}}
        {{-- ============================================ --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">Communication</div>
            
            <a href="{{ route('communication.inbox') }}" class="sapta-nav-item {{ request()->routeIs('communication.inbox') ? 'active' : '' }}">
                <i class="fas fa-inbox"></i>
                <span class="sapta-nav-text">Inbox</span>
                @if($unreadCount > 0)
                    <span class="sapta-nav-badge">{{ $unreadCount }}</span>
                @endif
            </a>
            
            <a href="{{ route('notifications.index') }}" class="sapta-nav-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="fas fa-bell"></i>
                <span class="sapta-nav-text">Notifications</span>
            </a>
        </div>

        {{-- ============================================ --}}
        {{-- REPORTS — Kwa wote --}}
        {{-- ============================================ --}}
        <div class="sapta-nav-section">
            <div class="sapta-nav-section-title">Reports</div>
            
            <a href="{{ route('reports.index') }}" class="sapta-nav-item {{ request()->routeIs('reports.index') ? 'active' : '' }}">
                <i class="fas fa-file-lines"></i>
                <span class="sapta-nav-text">All Reports</span>
            </a>
        </div>

    </nav>

    {{-- FOOTER — Logout --}}
    <div class="sapta-sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sapta-nav-item sapta-logout-button">
                <i class="fas fa-right-from-bracket"></i>
                <span class="sapta-nav-text">Logout</span>
            </button>
        </form>
    </div>

</aside>