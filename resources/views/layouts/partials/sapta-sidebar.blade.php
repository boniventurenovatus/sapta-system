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
                
                <a href="javascript:void(0)" class="sapta-nav-item sapta-nav-toggle {{ request()->routeIs('employees.*') ? 'active' : '' }}" data-target="employees-submenu">
                      <i class="fas fa-users"></i>
                      <span class="sapta-nav-text">Employees</span>
                      <i class="fas fa-angle-right sapta-nav-arrow" style="margin-left:auto; font-size:0.75rem; transition:transform 0.2s;"></i>
                  </a>
                  <div class="sapta-nav-submenu" id="employees-submenu" style="display:none;">
                      <a href="{{ route('employees.index') }}" class="sapta-nav-subitem {{ request()->routeIs('employees.index') ? 'active' : '' }}">
                          <i class="fas fa-list"></i>
                          <span class="sapta-nav-text">Employee List</span>
                      </a>
                      <a href="{{ route('employees.create') }}" class="sapta-nav-subitem {{ request()->routeIs('employees.create') ? 'active' : '' }}">
                          <i class="fas fa-user-plus"></i>
                          <span class="sapta-nav-text">Add Employee</span>
                      </a>
                  </div>
                
                <a href="javascript:void(0)" class="sapta-nav-item sapta-nav-toggle {{ request()->routeIs('users.*') ? 'active' : '' }}" data-target="users-submenu">
                      <i class="fas fa-user-gear"></i>
                      <span class="sapta-nav-text">System Users</span>
                      <i class="fas fa-angle-right sapta-nav-arrow" style="margin-left:auto; font-size:0.75rem; transition:transform 0.2s;"></i>
                  </a>
                  <div class="sapta-nav-submenu" id="users-submenu" style="display:none;">
                      <a href="{{ route('users.index') }}" class="sapta-nav-subitem {{ request()->routeIs('users.index') ? 'active' : '' }}">
                          <i class="fas fa-list"></i>
                          <span class="sapta-nav-text">User List</span>
                      </a>
                      <a href="{{ route('users.create') }}" class="sapta-nav-subitem {{ request()->routeIs('users.create') ? 'active' : '' }}">
                          <i class="fas fa-user-plus"></i>
                          <span class="sapta-nav-text">Add User</span>
                      </a>
                  </div>
                
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
                
                <a href="javascript:void(0)" class="sapta-nav-item sapta-nav-toggle {{ request()->routeIs('settings.*') ? 'active' : '' }}" data-target="settings-submenu">
                      <i class="fas fa-gear"></i>
                      <span class="sapta-nav-text">Settings</span>
                      <i class="fas fa-angle-right sapta-nav-arrow" style="margin-left:auto; font-size:0.75rem; transition:transform 0.2s;"></i>
                  </a>
                  <div class="sapta-nav-submenu" id="settings-submenu" style="display:none;">
                      <a href="{{ route('settings.index') }}" class="sapta-nav-subitem {{ request()->routeIs('settings.index') ? 'active' : '' }}">
                          <i class="fas fa-sliders"></i>
                          <span class="sapta-nav-text">General Settings</span>
                      </a>
                  </div>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- EXECUTIVE (BOD, CEO) --}}
        {{-- ============================================ --}}
        @if($isExecutive)
            <div class="sapta-nav-section">
                <div class="sapta-nav-section-title">Executive</div>
                
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
                
                </a>
                
                <a href="javascript:void(0)" class="sapta-nav-item sapta-nav-toggle {{ request()->routeIs('departments.*') ? 'active' : '' }}" data-target="departments-submenu">
                      <i class="fas fa-sitemap"></i>
                      <span class="sapta-nav-text">Departments</span>
                      <i class="fas fa-angle-right sapta-nav-arrow" style="margin-left:auto; font-size:0.75rem; transition:transform 0.2s;"></i>
                  </a>
                  <div class="sapta-nav-submenu" id="departments-submenu" style="display:none;">
                      <a href="{{ route('departments.index') }}" class="sapta-nav-subitem {{ request()->routeIs('departments.index') ? 'active' : '' }}">
                          <i class="fas fa-list"></i>
                          <span class="sapta-nav-text">Department List</span>
                      </a>
                      <a href="{{ route('departments.create') }}" class="sapta-nav-subitem {{ request()->routeIs('departments.create') ? 'active' : '' }}">
                          <i class="fas fa-plus"></i>
                          <span class="sapta-nav-text">Add Department</span>
                      </a>
                  </div>
                
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
                
                </a>
                
                <a href="{{ route('departments.index') }}" class="sapta-nav-item {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                    <i class="fas fa-sitemap"></i>
                    <span class="sapta-nav-text">Departments</span>
                </a>
                
                <a href="{{ route('positions.index') }}" class="sapta-nav-item {{ request()->routeIs('positions.*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span class="sapta-nav-text">Positions</span>
                </a>
                
                <a href="javascript:void(0)" class="sapta-nav-item sapta-nav-toggle {{ request()->routeIs('attendances.*') ? 'active' : '' }}" data-target="attendances-submenu">
                      <i class="fas fa-clock"></i>
                      <span class="sapta-nav-text">Attendance</span>
                      <i class="fas fa-angle-right sapta-nav-arrow" style="margin-left:auto; font-size:0.75rem; transition:transform 0.2s;"></i>
                  </a>
                  <div class="sapta-nav-submenu" id="attendances-submenu" style="display:none;">
                      <a href="{{ route('attendances.index') }}" class="sapta-nav-subitem {{ request()->routeIs('attendances.index') ? 'active' : '' }}">
                          <i class="fas fa-list"></i>
                          <span class="sapta-nav-text">Attendance List</span>
                      </a>
                      <a href="{{ route('attendances.create') }}" class="sapta-nav-subitem {{ request()->routeIs('attendances.create') ? 'active' : '' }}">
                          <i class="fas fa-plus"></i>
                          <span class="sapta-nav-text">Add Attendance</span>
                      </a>
                  </div>
                
                <a href="javascript:void(0)" class="sapta-nav-item sapta-nav-toggle {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}" data-target="leave-requests-submenu">
                      <i class="fas fa-calendar-check"></i>
                      <span class="sapta-nav-text">Leave Requests</span>
                      <i class="fas fa-angle-right sapta-nav-arrow" style="margin-left:auto; font-size:0.75rem; transition:transform 0.2s;"></i>
                  </a>
                  <div class="sapta-nav-submenu" id="leave-requests-submenu" style="display:none;">
                      <a href="{{ route('leave-requests.index') }}" class="sapta-nav-subitem {{ request()->routeIs('leave-requests.index') ? 'active' : '' }}">
                          <i class="fas fa-list"></i>
                          <span class="sapta-nav-text">Leave List</span>
                      </a>
                      <a href="{{ route('leave-requests.create') }}" class="sapta-nav-subitem {{ request()->routeIs('leave-requests.create') ? 'active' : '' }}">
                          <i class="fas fa-plus"></i>
                          <span class="sapta-nav-text">Apply Leave</span>
                      </a>
                  </div>
                
                <a href="{{ route('trainings.index') }}" class="sapta-nav-item {{ request()->routeIs('trainings.*') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span class="sapta-nav-text">Trainings</span>
                </a>
                
                <a href="javascript:void(0)" class="sapta-nav-item sapta-nav-toggle {{ request()->routeIs('recruitment.*') ? 'active' : '' }}" data-target="recruitment-submenu">
                      <i class="fas fa-user-plus"></i>
                      <span class="sapta-nav-text">Recruitment</span>
                      <i class="fas fa-angle-right sapta-nav-arrow" style="margin-left:auto; font-size:0.75rem; transition:transform 0.2s;"></i>
                  </a>
                  <div class="sapta-nav-submenu" id="recruitment-submenu" style="display:none;">
                      <a href="{{ route('recruitment.index') }}" class="sapta-nav-subitem {{ request()->routeIs('recruitment.index') ? 'active' : '' }}">
                          <i class="fas fa-list"></i>
                          <span class="sapta-nav-text">Job Postings</span>
                      </a>
                      <a href="{{ route('recruitment.create') }}" class="sapta-nav-subitem {{ request()->routeIs('recruitment.create') ? 'active' : '' }}">
                          <i class="fas fa-plus"></i>
                          <span class="sapta-nav-text">Add Job</span>
                      </a>
                  </div>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- FINANCE --}}
        {{-- ============================================ --}}
        @if($isFinance || $isSuperAdmin)
            <div class="sapta-nav-section">
                <div class="sapta-nav-section-title">Finance</div>
                
                <a href="javascript:void(0)" class="sapta-nav-item sapta-nav-toggle {{ request()->routeIs('budgets.*') ? 'active' : '' }}" data-target="budgets-submenu">
                      <i class="fas fa-wallet"></i>
                      <span class="sapta-nav-text">Budgets</span>
                      <i class="fas fa-angle-right sapta-nav-arrow" style="margin-left:auto; font-size:0.75rem; transition:transform 0.2s;"></i>
                  </a>
                  <div class="sapta-nav-submenu" id="budgets-submenu" style="display:none;">
                      <a href="{{ route('budgets.index') }}" class="sapta-nav-subitem {{ request()->routeIs('budgets.index') ? 'active' : '' }}">
                          <i class="fas fa-list"></i>
                          <span class="sapta-nav-text">Budget List</span>
                      </a>
                      <a href="{{ route('budgets.create') }}" class="sapta-nav-subitem {{ request()->routeIs('budgets.create') ? 'active' : '' }}">
                          <i class="fas fa-plus"></i>
                          <span class="sapta-nav-text">Add Budget</span>
                      </a>
                  </div>
                
                <a href="{{ route('receipts.index') }}" class="sapta-nav-item {{ request()->routeIs('receipts.*') ? 'active' : '' }}">
                    <i class="fas fa-receipt"></i>
                    <span class="sapta-nav-text">Receipts</span>
                </a>
                
                <a href="javascript:void(0)" class="sapta-nav-item sapta-nav-toggle {{ request()->routeIs('payment-vouchers.*') ? 'active' : '' }}" data-target="payment-vouchers-submenu">
                      <i class="fas fa-money-check"></i>
                      <span class="sapta-nav-text">Payment Vouchers</span>
                      <i class="fas fa-angle-right sapta-nav-arrow" style="margin-left:auto; font-size:0.75rem; transition:transform 0.2s;"></i>
                  </a>
                  <div class="sapta-nav-submenu" id="payment-vouchers-submenu" style="display:none;">
                      <a href="{{ route('payment-vouchers.index') }}" class="sapta-nav-subitem {{ request()->routeIs('payment-vouchers.index') ? 'active' : '' }}">
                          <i class="fas fa-list"></i>
                          <span class="sapta-nav-text">Voucher List</span>
                      </a>
                      <a href="{{ route('payment-vouchers.create') }}" class="sapta-nav-subitem {{ request()->routeIs('payment-vouchers.create') ? 'active' : '' }}">
                          <i class="fas fa-plus"></i>
                          <span class="sapta-nav-text">Add Voucher</span>
                      </a>
                  </div>
                
                <a href="javascript:void(0)" class="sapta-nav-item sapta-nav-toggle {{ request()->routeIs('payroll.*') ? 'active' : '' }}" data-target="payroll-submenu">
                    <i class="fas fa-money-bill-wave"></i>
                    <span class="sapta-nav-text">Payroll</span>
                    <i class="fas fa-angle-right sapta-nav-arrow" style="margin-left:auto; font-size:0.75rem; transition:transform 0.2s;"></i>
                </a>
                <div class="sapta-nav-submenu" id="payroll-submenu" style="display: none;">

                    <a href="{{ route('payroll.salaries') }}" class="sapta-nav-subitem {{ request()->routeIs('payroll.salaries') && !request()->routeIs('payroll.salaries.create') ? 'active' : '' }}">
                        <i class="fas fa-money-bill"></i>
                        <span class="sapta-nav-text">Salaries</span>
                    </a>
                    <a href="{{ route('payroll.salaries.create') }}" class="sapta-nav-subitem {{ request()->routeIs('payroll.salaries.create') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle"></i>
                        <span class="sapta-nav-text">Add Salary</span>
                    </a>
                    <a href="{{ route('my-payslips') }}" class="sapta-nav-subitem {{ request()->routeIs('my-payslips') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span class="sapta-nav-text">My Payslips</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- PROJECTS & PROGRAMS --}}
        {{-- ============================================ --}}
        @if($isProgram || $isSuperAdmin)
            <div class="sapta-nav-section">
                <div class="sapta-nav-section-title">Projects & Programs</div>
                
                <a href="javascript:void(0)" class="sapta-nav-item sapta-nav-toggle {{ request()->routeIs('projects.*') ? 'active' : '' }}" data-target="projects-submenu">
                      <i class="fas fa-diagram-project"></i>
                      <span class="sapta-nav-text">Projects</span>
                      <i class="fas fa-angle-right sapta-nav-arrow" style="margin-left:auto; font-size:0.75rem; transition:transform 0.2s;"></i>
                  </a>
                  <div class="sapta-nav-submenu" id="projects-submenu" style="display:none;">
                      <a href="{{ route('projects.index') }}" class="sapta-nav-subitem {{ request()->routeIs('projects.index') ? 'active' : '' }}">
                          <i class="fas fa-list"></i>
                          <span class="sapta-nav-text">Project List</span>
                      </a>
                      <a href="{{ route('projects.create') }}" class="sapta-nav-subitem {{ request()->routeIs('projects.create') ? 'active' : '' }}">
                          <i class="fas fa-plus"></i>
                          <span class="sapta-nav-text">Add Project</span>
                      </a>
                  </div>
                
                <a href="{{ route('tasks.index') }}" class="sapta-nav-item {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                    <i class="fas fa-list-check"></i>
                    <span class="sapta-nav-text">Tasks</span>
                </a>
                
                <a href="javascript:void(0)" class="sapta-nav-item sapta-nav-toggle {{ request()->routeIs('documents.*') ? 'active' : '' }}" data-target="documents-submenu">
                      <i class="fas fa-folder"></i>
                      <span class="sapta-nav-text">Documents</span>
                      <i class="fas fa-angle-right sapta-nav-arrow" style="margin-left:auto; font-size:0.75rem; transition:transform 0.2s;"></i>
                  </a>
                  <div class="sapta-nav-submenu" id="documents-submenu" style="display:none;">
                      <a href="{{ route('documents.index') }}" class="sapta-nav-subitem {{ request()->routeIs('documents.index') ? 'active' : '' }}">
                          <i class="fas fa-list"></i>
                          <span class="sapta-nav-text">Document List</span>
                      </a>
                      <a href="{{ route('documents.create') }}" class="sapta-nav-subitem {{ request()->routeIs('documents.create') ? 'active' : '' }}">
                          <i class="fas fa-plus"></i>
                          <span class="sapta-nav-text">Upload Document</span>
                      </a>
                  </div>
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


<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.sapta-nav-toggle').forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            var targetId = this.getAttribute('data-target');
            var submenu = document.getElementById(targetId);
            if (!submenu) return;
            
            if (submenu.style.display === 'none' || submenu.style.display === '') {
                submenu.style.display = 'block';
                this.classList.add('open');
            } else {
                submenu.style.display = 'none';
                this.classList.remove('open');
            }
        });
    });
    
    var path = window.location.pathname;
    var submenuMap = {
        '/employees': 'employees-submenu',
        '/users': 'users-submenu',
        '/settings': 'settings-submenu',
        '/recruitment': 'recruitment-submenu',
        '/projects': 'projects-submenu',
        '/payroll': 'payroll-submenu',
        '/my-payslips': 'payroll-submenu',
        '/departments': 'departments-submenu',
        '/attendances': 'attendances-submenu',
        '/leave-requests': 'leave-requests-submenu',
        '/budgets': 'budgets-submenu',
        '/payment-vouchers': 'payment-vouchers-submenu',
        '/documents': 'documents-submenu'
    };
    
    Object.keys(submenuMap).forEach(function (prefix) {
        if (path.startsWith(prefix)) {
            var submenu = document.getElementById(submenuMap[prefix]);
            if (submenu) {
                submenu.style.display = 'block';
                document.querySelectorAll('.sapta-nav-toggle').forEach(function (t) {
                    if (t.getAttribute('data-target') === submenuMap[prefix]) {
                        t.classList.add('open');
                    }
                });
            }
        }
    });
});
</script>
</aside>
