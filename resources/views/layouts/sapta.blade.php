<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SAPTA Management System')</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/sapta-forms.css') }}">

    <style>
        /* TOPBAR */
        .tb-topbar { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: 0.75rem 1.5rem; background: #fff; border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 100; }
        .tb-left { display: flex; align-items: center; gap: 1rem; flex: 1; min-width: 0; }
        .tb-title { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .tb-search { display: flex; align-items: center; gap: 0.5rem; background: #f1f5f9; padding: 0.5rem 0.875rem; border-radius: 0.625rem; max-width: 400px; flex: 1; transition: all 0.2s; }
        .tb-search:focus-within { background: #e2e8f0; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .tb-search i { color: #94a3b8; font-size: 0.85rem; }
        .tb-search input { border: none; background: transparent; outline: none; font-size: 0.85rem; color: #334155; width: 100%; font-family: inherit; }
        .tb-search input::placeholder { color: #94a3b8; }

        .tb-right { display: flex; align-items: center; gap: 0.5rem; }

        .tb-icon-btn { width: 2.5rem; height: 2.5rem; border-radius: 0.625rem; background: #f8fafc; border: 1.5px solid #e2e8f0; color: #475569; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; position: relative; text-decoration: none; font-size: 1rem; }
        .tb-icon-btn:hover { background: #eff6ff; border-color: #93c5fd; color: #2563eb; transform: translateY(-1px); }
        .tb-icon-btn .tb-badge { position: absolute; top: -4px; right: -4px; background: #dc2626; color: #fff; font-size: 0.65rem; font-weight: 800; padding: 0.15rem 0.4rem; border-radius: 999px; min-width: 1.1rem; text-align: center; border: 2px solid #fff; }

        /* DROPDOWN */
        .tb-dropdown-wrap { position: relative; }
        .tb-dropdown { position: absolute; top: calc(100% + 0.5rem); right: 0; background: #fff; border-radius: 0.875rem; box-shadow: 0 12px 40px rgba(0,0,0,0.12); border: 1px solid #e2e8f0; min-width: 320px; max-width: 400px; z-index: 1000; opacity: 0; visibility: hidden; transform: translateY(-8px); transition: all 0.2s ease; overflow: hidden; }
        .tb-dropdown.open { opacity: 1; visibility: visible; transform: translateY(0); }
        .tb-dropdown-header { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fafbfc; }
        .tb-dropdown-header h3 { font-size: 0.9rem; font-weight: 800; color: #1e293b; margin: 0; }
        .tb-dropdown-header a { font-size: 0.75rem; color: #2563eb; text-decoration: none; font-weight: 700; }
        .tb-dropdown-header a:hover { text-decoration: underline; }
        .tb-dropdown-body { max-height: 400px; overflow-y: auto; }
        .tb-dropdown-item { display: flex; align-items: flex-start; gap: 0.75rem; padding: 0.875rem 1.25rem; border-bottom: 1px solid #f8fafc; text-decoration: none; color: inherit; transition: background 0.15s; }
        .tb-dropdown-item:hover { background: #f8fafc; }
        .tb-dropdown-item.unread { background: #eff6ff; }
        .tb-dropdown-item.unread:hover { background: #dbeafe; }
        .tb-dropdown-item:last-child { border-bottom: none; }
        .tb-dd-icon { width: 2.25rem; height: 2.25rem; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0; }
        .tb-dd-icon.info { background: #dbeafe; color: #2563eb; }
        .tb-dd-icon.success { background: #d1fae5; color: #059669; }
        .tb-dd-icon.warning { background: #fef3c7; color: #d97706; }
        .tb-dd-icon.danger { background: #fee2e2; color: #dc2626; }
        .tb-dd-content { flex: 1; min-width: 0; }
        .tb-dd-title { font-size: 0.82rem; font-weight: 700; color: #1e293b; margin: 0 0 0.15rem; }
        .tb-dd-msg { font-size: 0.78rem; color: #64748b; margin: 0 0 0.2rem; line-height: 1.3; }
        .tb-dd-time { font-size: 0.68rem; color: #94a3b8; }
        .tb-dd-empty { padding: 2rem 1rem; text-align: center; color: #94a3b8; font-size: 0.85rem; }
        .tb-dd-footer { padding: 0.75rem 1.25rem; border-top: 1px solid #f1f5f9; text-align: center; background: #fafbfc; }
        .tb-dd-footer a { font-size: 0.8rem; color: #2563eb; text-decoration: none; font-weight: 700; }

        /* PROFILE */
        .tb-profile { display: flex; align-items: center; gap: 0.6rem; padding: 0.4rem 0.75rem 0.4rem 0.4rem; border-radius: 0.625rem; background: #f8fafc; border: 1.5px solid #e2e8f0; cursor: pointer; transition: all 0.2s; text-decoration: none; color: inherit; }
        .tb-profile:hover { background: #eff6ff; border-color: #93c5fd; }
        .tb-avatar { width: 2rem; height: 2rem; border-radius: 50%; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.75rem; flex-shrink: 0; }
        .tb-profile-info { display: flex; flex-direction: column; line-height: 1.1; }
        .tb-profile-info strong { font-size: 0.82rem; color: #1e293b; font-weight: 700; }
        .tb-profile-info small { font-size: 0.7rem; color: #94a3b8; }
        .tb-profile .caret { color: #94a3b8; font-size: 0.7rem; margin-left: 0.25rem; transition: transform 0.2s; }
        .tb-dropdown-wrap.open .tb-profile .caret { transform: rotate(180deg); }

        .tb-profile-dropdown { min-width: 260px; }
        .tb-profile-header { padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
        .tb-profile-header strong { display: block; font-size: 0.9rem; color: #1e293b; font-weight: 700; margin-bottom: 0.15rem; }
        .tb-profile-header small { font-size: 0.75rem; color: #94a3b8; }
        .tb-profile-menu { padding: 0.5rem; }
        .tb-profile-menu a { display: flex; align-items: center; gap: 0.75rem; padding: 0.65rem 0.75rem; border-radius: 0.5rem; font-size: 0.85rem; color: #334155; text-decoration: none; transition: all 0.15s; font-weight: 600; }
        .tb-profile-menu a:hover { background: #eff6ff; color: #2563eb; }
        .tb-profile-menu a i { width: 1.25rem; text-align: center; color: #64748b; font-size: 0.9rem; }
        .tb-profile-menu a:hover i { color: #2563eb; }
        .tb-profile-menu a.logout { color: #dc2626; }
        .tb-profile-menu a.logout:hover { background: #fee2e2; color: #991b1b; }
        .tb-profile-menu a.logout i { color: #dc2626; }
        .tb-profile-divider { height: 1px; background: #f1f5f9; margin: 0.5rem 0; }

        @media (max-width: 768px) {
            .tb-search { display: none; }
            .tb-profile-info { display: none; }
            .tb-topbar { padding: 0.625rem 1rem; }
            .tb-title { font-size: 0.95rem; }
        }
    
        /* ============================================================
           PRINT — ficha sidebar, topbar, buttons
           ============================================================ */
        @media print {
            .sidebar, .topbar, .tb-topbar, .sidebar-toggle, .btn, button, .no-print,
            nav, header, .navbar, .page-header .btn, .rp-header-actions,
            .rp-filter, .pagination, .actions, .table-actions {
                display: none !important;
            }
            body, .main-content, .content {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
            }
            .rp-card, .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                break-inside: avoid;
            }
            .rp-stats {
                display: grid !important;
                grid-template-columns: repeat(4, 1fr) !important;
            }
            table { font-size: 11px !important; }
            th, td { padding: 6px 8px !important; }
        }
</style>

    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @stack('scripts')
</head>

<body>
    @include('layouts.partials.sapta-sidebar')

    <div class="sapta-sidebar-overlay" id="saptaSidebarOverlay"></div>

    <button type="button" class="sapta-sidebar-toggle" id="saptaSidebarToggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
        <i class="fas fa-chevron-left"></i>
    </button>

    <div class="sapta-mobile-overlay" id="saptaMobileOverlay" onclick="closeMobileSidebar()"></div>

    <div class="sapta-main-area" id="saptaMainArea">

        <header class="tb-topbar">
            <div class="tb-left">
                <h1 class="tb-title">@yield('page-title', 'Dashboard')</h1>
                <div class="tb-search-wrap">
                    <div class="tb-search">
                        <i class="fas fa-magnifying-glass"></i>
                        <input type="text" placeholder="Global search..." id="globalSearch" autocomplete="off">
                    </div>
                    <div class="tb-search-dropdown" id="globalSearchDropdown"></div>
                </div>
            </div>

            <div class="tb-right">
                @auth
                    @php
                        $hasNotifications = \Schema::hasTable('notifications');
                        $unreadCount = $hasNotifications ? auth()->user()->unreadNotifications->count() : 0;
                        $recentNotifications = $hasNotifications ? auth()->user()->notifications()->limit(5)->get() : collect();
                    @endphp

                    {{-- NOTIFICATIONS BELL --}}
                    <div class="tb-dropdown-wrap" id="notifWrap">
                        <button type="button" class="tb-icon-btn" onclick="toggleDropdown('notifWrap')" title="Notifications">
                            <i class="fas fa-bell"></i>
                            @if($unreadCount > 0)
                                <span class="tb-badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                            @endif
                        </button>

                        <div class="tb-dropdown" id="notifDropdown">
                            <div class="tb-dropdown-header">
                                <h3>Notifications</h3>
                                <a href="{{ route('notifications.index') }}">View All</a>
                            </div>
                            <div class="tb-dropdown-body">
                                @forelse($recentNotifications as $notif)
                                    @php
                                        $data = $notif->data;
                                        $type = $data['type'] ?? 'info';
                                        $icon = $data['icon'] ?? 'bell';
                                    @endphp
                                    <a href="{{ $data['url'] ?? route('notifications.index') }}" class="tb-dropdown-item {{ $notif->read_at ? '' : 'unread' }}">
                                        <div class="tb-dd-icon {{ $type }}">
                                            <i class="fas fa-{{ $icon }}"></i>
                                        </div>
                                        <div class="tb-dd-content">
                                            <p class="tb-dd-title">{{ $data['title'] ?? 'Notification' }}</p>
                                            <p class="tb-dd-msg">{{ \Illuminate\Support\Str::limit($data['message'] ?? '', 60) }}</p>
                                            <span class="tb-dd-time"><i class="fas fa-clock"></i> {{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                    </a>
                                @empty
                                    <div class="tb-dd-empty">
                                        <i class="fas fa-bell-slash" style="font-size:1.5rem; display:block; margin-bottom:0.5rem;"></i>
                                        No notifications
                                    </div>
                                @endforelse
                            </div>
                            <div class="tb-dd-footer">
                                <a href="{{ route('notifications.index') }}">View All Notifications ?</a>
                            </div>
                        </div>
                    </div>

                    {{-- PROFILE --}}
                    <div class="tb-dropdown-wrap" id="profileWrap">
                        <button type="button" class="tb-profile" onclick="toggleDropdown('profileWrap')">
                            <div class="tb-avatar">
                                {{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 2)) }}
                            </div>
                            <div class="tb-profile-info">
                                <strong>{{ auth()->user()->username ?? 'User' }}</strong>
                                <small>{{ auth()->user()->roles->first()->name ?? 'User' }}</small>
                            </div>
                            <i class="fas fa-chevron-down caret"></i>
                        </button>

                        <div class="tb-dropdown tb-profile-dropdown" id="profileDropdown">
                            <div class="tb-profile-header">
                                <strong>{{ auth()->user()->username ?? 'User' }}</strong>
                                <small>{{ auth()->user()->email ?? '' }}</small>
                            </div>
                            <div class="tb-profile-menu">
                                <a href="{{ route('profile.show') }}"><i class="fas fa-user"></i> My Profile</a>
                                <a href="{{ route('profile.edit') }}"><i class="fas fa-user-pen"></i> Edit Profile</a>
                                <a href="{{ route('settings.index') }}"><i class="fas fa-gear"></i> Settings</a>
                                <a href="{{ route('profile.change-password') }}"><i class="fas fa-key"></i> Change Password</a>
                                <div class="tb-profile-divider"></div>
                                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                                    @csrf
                                    <button type="submit" style="width:100%; border:none; background:none; cursor:pointer; text-align:left;" class="tb-profile-menu-link">
                                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="logout">
                                            <i class="fas fa-right-from-bracket"></i> Logout
                                        </a>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </header>

        <main class="sapta-content">
            <div class="sapta-container">
                



                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('saptaSidebar');
            const mainArea = document.getElementById('saptaMainArea');
            if (!sidebar || !mainArea) return;

            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('mobile-open');
                const overlay = document.getElementById('saptaMobileOverlay');
                if (overlay) overlay.classList.toggle('show', sidebar.classList.contains('mobile-open'));
                return;
            }
            sidebar.classList.toggle('collapsed');
            mainArea.classList.toggle('expanded');
            localStorage.setItem('saptaSidebarCollapsed', sidebar.classList.contains('collapsed') ? '1' : '0');
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('saptaSidebar');
            const overlay = document.getElementById('saptaMobileOverlay');
            if (!sidebar || !overlay) return;
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('show');
        }

        function toggleDropdown(id) {
            const wrap = document.getElementById(id);
            const dropdown = wrap.querySelector('.tb-dropdown');
            const isOpen = dropdown.classList.contains('open');

            // Close all dropdowns
            document.querySelectorAll('.tb-dropdown.open').forEach(d => d.classList.remove('open'));
            document.querySelectorAll('.tb-dropdown-wrap.open').forEach(w => w.classList.remove('open'));

            if (!isOpen) {
                dropdown.classList.add('open');
                wrap.classList.add('open');
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.tb-dropdown-wrap')) {
                document.querySelectorAll('.tb-dropdown.open').forEach(d => d.classList.remove('open'));
                document.querySelectorAll('.tb-dropdown-wrap.open').forEach(w => w.classList.remove('open'));
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('saptaSidebar');
            const mainArea = document.getElementById('saptaMainArea');
            if (!sidebar || !mainArea) return;

            if (localStorage.getItem('saptaSidebarCollapsed') === '1' && window.innerWidth > 768) {
                sidebar.classList.add('collapsed');
                mainArea.classList.add('expanded');
            }
        });

        document.addEventListener('keydown', function (event) {
            if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'b') {
                event.preventDefault();
                toggleSidebar();
            }
        });

        window.addEventListener('resize', function () {
            if (window.innerWidth > 768) closeMobileSidebar();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/sapta-delete.js') }}"></script>
    @stack('scripts')



<!-- GLOBAL SEARCH JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var input = document.getElementById('globalSearch');
    var dropdown = document.getElementById('globalSearchDropdown');
    if (!input || !dropdown) return;

    var debounceTimer = null;
    var currentResults = [];
    var activeIndex = -1;

    function closeDropdown() {
        dropdown.classList.remove('open');
        activeIndex = -1;
    }

    function escapeHtml(s) {
        if (!s) return '';
        return String(s).replace(/[&<>"']/g, function(m) {
            return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m];
        });
    }

    function renderResults(results, query) {
        if (!results.length) {
            dropdown.innerHTML = '<div class="tb-search-empty"><i class="fas fa-search-minus"></i>Hakuna matokeo kwa "<strong>' + escapeHtml(query) + '</strong>"</div><div class="tb-search-footer"><a href="/search?q=' + encodeURIComponent(query) + '">Tafuta kwa undani &rarr;</a></div>';
            dropdown.classList.add('open');
            return;
        }

        var grouped = {};
        results.forEach(function(r) {
            if (!grouped[r.type]) grouped[r.type] = [];
            grouped[r.type].push(r);
        });

        var html = '';
        Object.keys(grouped).forEach(function(type) {
            html += '<div class="tb-search-section">';
            html += '<div class="tb-search-section-title">' + escapeHtml(type) + '</div>';
            grouped[type].forEach(function(r) {
                html += '<a href="' + escapeHtml(r.url) + '" class="tb-search-result" data-url="' + escapeHtml(r.url) + '">';
                html += '<div class="tb-search-result-icon"><i class="fas ' + escapeHtml(r.icon || 'fa-file') + '"></i></div>';
                html += '<div class="tb-search-result-content">';
                html += '<p class="tb-search-result-title">' + escapeHtml(r.title) + '</p>';
                html += '<p class="tb-search-result-sub">' + escapeHtml(r.description || '') + '</p>';
                html += '</div>';
                html += '<span class="tb-search-result-type">' + escapeHtml(r.type) + '</span>';
                html += '</a>';
            });
            html += '</div>';
        });

        html += '<div class="tb-search-footer"><a href="/search?q=' + encodeURIComponent(query) + '">Ona matokeo yote &rarr;</a></div>';

        dropdown.innerHTML = html;
        dropdown.classList.add('open');

        var items = dropdown.querySelectorAll('.tb-search-result');
        activeIndex = -1;
        items.forEach(function(item, i) {
            item.addEventListener('mouseenter', function() {
                items.forEach(function(el) { el.classList.remove('active'); });
                item.classList.add('active');
                activeIndex = i;
            });
        });
        currentResults = items;
    }

    input.addEventListener('input', function() {
        var q = this.value.trim();
        clearTimeout(debounceTimer);

        if (q.length < 2) {
            closeDropdown();
            return;
        }

        dropdown.innerHTML = '<div class="tb-search-loading"><i class="fas fa-spinner fa-spin"></i> Inatafuta...</div>';
        dropdown.classList.add('open');

        debounceTimer = setTimeout(function() {
            fetch('/search/live?q=' + encodeURIComponent(q), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) { renderResults(data, q); })
            .catch(function() {
                dropdown.innerHTML = '<div class="tb-search-empty"><i class="fas fa-exclamation-triangle"></i> Tatizo. Jaribu tena.</div>';
            });
        }, 250);
    });

    input.addEventListener('keydown', function(e) {
        if (!dropdown.classList.contains('open')) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (activeIndex < currentResults.length - 1) {
                activeIndex++;
                currentResults.forEach(function(el) { el.classList.remove('active'); });
                currentResults[activeIndex].classList.add('active');
                currentResults[activeIndex].scrollIntoView({ block: 'nearest' });
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (activeIndex > 0) {
                activeIndex--;
                currentResults.forEach(function(el) { el.classList.remove('active'); });
                currentResults[activeIndex].classList.add('active');
                currentResults[activeIndex].scrollIntoView({ block: 'nearest' });
            }
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (activeIndex >= 0 && currentResults[activeIndex]) {
                window.location.href = currentResults[activeIndex].dataset.url;
            } else if (this.value.trim().length >= 2) {
                window.location.href = '/search?q=' + encodeURIComponent(this.value.trim());
            }
        } else if (e.key === 'Escape') {
            closeDropdown();
            input.blur();
        }
    });

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.tb-search-wrap')) {
            closeDropdown();
        }
    });
});
</script>
    <script src="{{ asset('js/sapta-modal.js') }}"></script>
    <script src="{{ asset('js/sapta-actions.js') }}"></script>

    
    <!-- ============================================================ -->
    <!-- SAPTA Toast Notifications (Inline Styles — no Tailwind)      -->
    <!-- ============================================================ -->
    @if(session('success'))
    <div id="sapta-toast-success" style="
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 99999;
        max-width: 720px;
        min-width: 480px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #ffffff;
        padding: 32px 40px;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35), 0 0 0 4px rgba(16,185,129,0.25);
        display: flex;
        align-items: center;
        gap: 24px;
        font-family: system-ui, -apple-system, 'Segoe UI', sans-serif;
        animation: saptaSlideInRight 0.45s cubic-bezier(0.22, 1, 0.36, 1);
    ">
        <div style="
            flex-shrink: 0;
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,0.22);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #ffffff;
        ">
            <i class="fas fa-check-circle"></i>
        </div>
        <div style="flex: 1;">
            <div style="
                font-size: 32px;
                font-weight: 900;
                line-height: 1.1;
                margin-bottom: 6px;
                letter-spacing: -0.5px;
            ">Success!</div>
            <div style="
                font-size: 20px;
                font-weight: 600;
                opacity: 0.96;
                line-height: 1.35;
            ">{{ session('success') }}</div>
        </div>
        <button onclick="document.getElementById('sapta-toast-success').remove()" style="
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            background: rgba(255,255,255,0.22);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            cursor: pointer;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        " onmouseover="this.style.background='rgba(255,255,255,0.35)'" onmouseout="this.style.background='rgba(255,255,255,0.22)'">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <script>
        setTimeout(function() {
            var t = document.getElementById('sapta-toast-success');
            if (t) {
                t.style.transition = 'opacity 0.55s ease, transform 0.55s ease';
                t.style.opacity = '0';
                t.style.transform = 'translateX(120%)';
                setTimeout(function() { t.remove(); }, 600);
            }
        }, 5000);
    </script>
    @endif

    @if(session('error'))
    <div id="sapta-toast-error" style="
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 99999;
        max-width: 720px;
        min-width: 480px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff;
        padding: 32px 40px;
        border-radius: 24px;
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.35), 0 0 0 4px rgba(239,68,68,0.25);
        display: flex;
        align-items: center;
        gap: 24px;
        font-family: system-ui, -apple-system, 'Segoe UI', sans-serif;
        animation: saptaSlideInRight 0.45s cubic-bezier(0.22, 1, 0.36, 1);
    ">
        <div style="
            flex-shrink: 0;
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,0.22);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: #ffffff;
        ">
            <i class="fas fa-exclamation-circle"></i>
        </div>
        <div style="flex: 1;">
            <div style="
                font-size: 32px;
                font-weight: 900;
                line-height: 1.1;
                margin-bottom: 6px;
                letter-spacing: -0.5px;
            ">Error!</div>
            <div style="
                font-size: 20px;
                font-weight: 600;
                opacity: 0.96;
                line-height: 1.35;
            ">{{ session('error') }}</div>
        </div>
        <button onclick="document.getElementById('sapta-toast-error').remove()" style="
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            background: rgba(255,255,255,0.22);
            border: none;
            border-radius: 12px;
            color: #ffffff;
            cursor: pointer;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        " onmouseover="this.style.background='rgba(255,255,255,0.35)'" onmouseout="this.style.background='rgba(255,255,255,0.22)'">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <script>
        setTimeout(function() {
            var t = document.getElementById('sapta-toast-error');
            if (t) {
                t.style.transition = 'opacity 0.55s ease, transform 0.55s ease';
                t.style.opacity = '0';
                t.style.transform = 'translateX(120%)';
                setTimeout(function() { t.remove(); }, 600);
            }
        }, 6500);
    </script>
    @endif

    <style>
        @keyframes saptaSlideInRight {
            from { transform: translateX(120%); opacity: 0; }
            to   { transform: translateX(0);    opacity: 1; }
        }
    
        /* ============================================================
           PRINT — ficha sidebar, topbar, buttons
           ============================================================ */
        @media print {
            .sidebar, .topbar, .tb-topbar, .sidebar-toggle, .btn, button, .no-print,
            nav, header, .navbar, .page-header .btn, .rp-header-actions,
            .rp-filter, .pagination, .actions, .table-actions {
                display: none !important;
            }
            body, .main-content, .content {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
            }
            .rp-card, .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                break-inside: avoid;
            }
            .rp-stats {
                display: grid !important;
                grid-template-columns: repeat(4, 1fr) !important;
            }
            table { font-size: 11px !important; }
            th, td { padding: 6px 8px !important; }
        }
</style>


<script>
/* ============================================================
   SAPTA CONFIRM — SweetAlert2 based
   Badilisha confirm() zote kuwa SweetAlert2
   ============================================================ */
window.saptaConfirm = function(event, message, options) {
    if (event) event.preventDefault();
    
    const link = event ? event.currentTarget : null;
    const href = link ? link.href : null;
    const method = (link && link.dataset.method) ? link.dataset.method : 'GET';
    const isDanger = message.toLowerCase().includes('delete') 
        || message.toLowerCase().includes('deactivate')
        || message.toLowerCase().includes('suspend')
        || message.toLowerCase().includes('terminate');
    
    Swal.fire({
        title: options && options.title ? options.title : (isDanger ? 'Are you sure?' : 'Confirm'),
        text: message,
        icon: isDanger ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonColor: isDanger ? '#dc2626' : '#3b82f6',
        cancelButtonColor: '#64748b',
        confirmButtonText: options && options.confirmText ? options.confirmText : (isDanger ? 'Yes, continue' : 'OK'),
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true,
        customClass: {
            popup: 'sapta-swal-popup',
            title: 'sapta-swal-title',
            confirmButton: 'sapta-swal-confirm',
            cancelButton: 'sapta-swal-cancel',
        }
    }).then((result) => {
        if (result.isConfirmed && href) {
            if (method === 'DELETE') {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = href;
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = document.querySelector('meta[name="csrf-token"]').content;
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(csrf);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            } else {
                window.location.href = href;
            }
        }
    });
    
    return false;
};

/* Badilisha native confirm() — onyo */
window._originalConfirm = window.confirm;
window.confirm = function(message) {
    console.warn('Native confirm() imeitwa. Tumia saptaConfirm() badala yake.');
    return _originalConfirm(message);
};
</script>

<style>
/* SweetAlert2 custom styling */
.sapta-swal-popup {
    border-radius: 16px !important;
    padding: 28px !important;
    font-family: inherit !important;
}
.sapta-swal-title {
    font-size: 20px !important;
    font-weight: 800 !important;
    color: #1a1a2e !important;
}
.swal2-html-container {
    font-size: 14px !important;
    color: #64748b !important;
}
.sapta-swal-confirm,
.sapta-swal-cancel {
    padding: 10px 24px !important;
    border-radius: 10px !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    border: none !important;
}
</style>

<script>
/* ============================================================
   SAPTA FORM CONFIRM — SweetAlert2 kwa forms
   ============================================================ */
window.saptaFormConfirm = function(event, message, options) {
    if (event) event.preventDefault();
    
    const form = event ? event.target : null;
    if (!form) return false;
    
    const isDanger = message.toLowerCase().includes('delete') 
        || message.toLowerCase().includes('deactivate')
        || message.toLowerCase().includes('suspend')
        || message.toLowerCase().includes('terminate');
    
    Swal.fire({
        title: options && options.title ? options.title : (isDanger ? 'Are you sure?' : 'Confirm'),
        text: message,
        icon: isDanger ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonColor: isDanger ? '#dc2626' : '#3b82f6',
        cancelButtonColor: '#64748b',
        confirmButtonText: options && options.confirmText ? options.confirmText : (isDanger ? 'Yes, continue' : 'OK'),
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    
    return false;
};
</script>
</body>
</html>
