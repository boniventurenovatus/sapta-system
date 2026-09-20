<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{
        sidebarOpen: false,
        employeesOpen: {{ request()->routeIs('employees.*') ? 'true' : 'false' }},
        usersOpen: {{ request()->routeIs('users.*') ? 'true' : 'false' }},
        profileOpen: false
    }"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @hasSection('title')
            @yield('title') - SAPTA System
        @else
            SAPTA System
        @endif
    </title>

    {{-- Tailwind --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: [
                            'Figtree',
                            'ui-sans-serif',
                            'system-ui',
                            'sans-serif'
                        ],
                    },
                },
            },
        };
    </script>

    {{-- Alpine --}}
    <script
        defer
        src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"
    ></script>

    {{-- Figtree --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>
        [x-cloak] {
            display: none !important;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Figtree', sans-serif;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.18);
            border-radius: 999px;
        }

        .sidebar-scroll {
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.18) transparent;
        }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">

    {{-- MOBILE OVERLAY --}}
    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-slate-950/60 lg:hidden"
    ></div>


    {{-- APPLICATION --}}
    <div class="min-h-screen">


        {{-- =========================================================
             SIDEBAR
             ========================================================= --}}
        <aside
            class="
                fixed inset-y-0 left-0 z-50
                flex w-72 flex-col
                bg-slate-950 text-white
                shadow-2xl
                transition-transform duration-300
                -translate-x-full
                lg:translate-x-0
            "
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >

            {{-- SIDEBAR HEADER --}}
            <div
                class="
                    flex h-20 shrink-0 items-center justify-between
                    border-b border-white/10 px-5
                "
            >
                <a
                    href="{{ route('dashboard') }}"
                    class="flex min-w-0 items-center gap-3"
                >
                    <div
                        class="
                            flex h-11 w-11 shrink-0 items-center justify-center
                            rounded-2xl bg-white
                            text-lg font-extrabold text-slate-950
                            shadow-lg
                        "
                    >
                        S
                    </div>

                    <div class="min-w-0">
                        <div class="text-lg font-extrabold tracking-tight">
                            SAPTA
                        </div>

                        <div
                            class="
                                truncate text-[10px] font-semibold
                                uppercase tracking-[0.2em]
                                text-slate-400
                            "
                        >
                            Management System
                        </div>
                    </div>
                </a>

                <button
                    type="button"
                    @click="sidebarOpen = false"
                    class="
                        rounded-lg p-2
                        text-slate-400
                        hover:bg-white/10 hover:text-white
                        lg:hidden
                    "
                    aria-label="Close menu"
                >
                    <svg
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>


            {{-- SIDEBAR NAVIGATION --}}
            <nav class="sidebar-scroll flex-1 overflow-y-auto px-3 py-5">

                <div
                    class="
                        mb-3 px-3 text-[10px] font-bold
                        uppercase tracking-[0.18em] text-slate-500
                    "
                >
                    Main Menu
                </div>


                {{-- DASHBOARD --}}
                <a
                    href="{{ route('dashboard') }}"
                    @click="sidebarOpen = false"
                    class="
                        mb-1 flex items-center gap-3 rounded-xl
                        px-3 py-3 text-sm font-semibold transition
                        {{ request()->routeIs('dashboard')
                            ? 'bg-white text-slate-950 shadow-lg'
                            : 'text-slate-300 hover:bg-white/10 hover:text-white'
                        }}
                    "
                >
                    <span
                        class="
                            flex h-9 w-9 shrink-0 items-center justify-center
                            rounded-lg
                            {{ request()->routeIs('dashboard')
                                ? 'bg-slate-950 text-white'
                                : 'bg-white/10 text-slate-300'
                            }}
                        "
                    >
                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6"
                            />
                        </svg>
                    </span>

                    Dashboard
                </a>


                {{-- =================================================
                     EMPLOYEES
                     ================================================= --}}
                <div class="mb-1">

                    <button
                        type="button"
                        @click="employeesOpen = !employeesOpen"
                        class="
                            flex w-full items-center gap-3
                            rounded-xl px-3 py-3
                            text-sm font-semibold
                            text-slate-300 transition
                            hover:bg-white/10 hover:text-white
                        "
                    >
                        <span
                            class="
                                flex h-9 w-9 shrink-0
                                items-center justify-center
                                rounded-lg bg-white/10
                            "
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1M12 12a4 4 0 100-8 4 4 0 000 8zM16 8a4 4 0 014 4M8 8a4 4 0 00-4 4"
                                />
                            </svg>
                        </span>

                        <span class="flex-1 text-left">
                            Employees
                        </span>

                        <svg
                            class="h-4 w-4 transition-transform duration-200"
                            :class="employeesOpen ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </button>


                    {{-- EMPLOYEE SUBMENU --}}
                    <div
                        x-cloak
                        x-show="employeesOpen"
                        x-transition
                        class="ml-6 space-y-1 border-l border-white/10 pl-3"
                    >

                        <a
                            href="{{ route('employees.index') }}"
                            @click="sidebarOpen = false"
                            class="
                                flex items-center gap-3 rounded-lg
                                px-3 py-2.5 text-sm transition
                                {{ request()->routeIs('employees.index')
                                    ? 'bg-white/10 font-semibold text-white'
                                    : 'text-slate-400 hover:bg-white/5 hover:text-white'
                                }}
                            "
                        >
                            <span
                                class="
                                    h-1.5 w-1.5 rounded-full
                                    {{ request()->routeIs('employees.index')
                                        ? 'bg-white'
                                        : 'bg-slate-600'
                                    }}
                                "
                            ></span>

                            All Employees
                        </a>


                        @if (Route::has('employees.create'))
                            <a
                                href="{{ route('employees.create') }}"
                                @click="sidebarOpen = false"
                                class="
                                    flex items-center gap-3 rounded-lg
                                    px-3 py-2.5 text-sm transition
                                    {{ request()->routeIs('employees.create')
                                        ? 'bg-white/10 font-semibold text-white'
                                        : 'text-slate-400 hover:bg-white/5 hover:text-white'
                                    }}
                                "
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-600"></span>
                                Add Employee
                            </a>
                        @endif


                        <div class="pt-2">
                            <div
                                class="
                                    px-3 pb-1 text-[9px] font-bold
                                    uppercase tracking-[0.15em]
                                    text-slate-600
                                "
                            >
                                Status
                            </div>

                            <a
                                href="{{ route('employees.index', ['status' => 'active']) }}"
                                class="flex items-center gap-3 rounded-lg px-3 py-2 text-xs text-slate-400 hover:bg-white/5 hover:text-white"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                                Active
                            </a>

                            <a
                                href="{{ route('employees.index', ['status' => 'inactive']) }}"
                                class="flex items-center gap-3 rounded-lg px-3 py-2 text-xs text-slate-400 hover:bg-white/5 hover:text-white"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-400"></span>
                                Inactive
                            </a>

                            <a
                                href="{{ route('employees.index', ['status' => 'on_leave']) }}"
                                class="flex items-center gap-3 rounded-lg px-3 py-2 text-xs text-slate-400 hover:bg-white/5 hover:text-white"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                On Leave
                            </a>

                            <a
                                href="{{ route('employees.index', ['status' => 'suspended']) }}"
                                class="flex items-center gap-3 rounded-lg px-3 py-2 text-xs text-slate-400 hover:bg-white/5 hover:text-white"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-orange-400"></span>
                                Suspended
                            </a>

                            <a
                                href="{{ route('employees.index', ['status' => 'terminated']) }}"
                                class="flex items-center gap-3 rounded-lg px-3 py-2 text-xs text-slate-400 hover:bg-white/5 hover:text-white"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-red-400"></span>
                                Terminated
                            </a>
                        </div>

                    </div>
                </div>


                {{-- =================================================
                     SYSTEM USERS
                     ================================================= --}}
                <div class="mb-1">

                    <button
                        type="button"
                        @click="usersOpen = !usersOpen"
                        class="
                            flex w-full items-center gap-3
                            rounded-xl px-3 py-3
                            text-sm font-semibold
                            text-slate-300 transition
                            hover:bg-white/10 hover:text-white
                        "
                    >
                        <span
                            class="
                                flex h-9 w-9 shrink-0
                                items-center justify-center
                                rounded-lg bg-white/10
                            "
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zM22 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"
                                />
                            </svg>
                        </span>

                        <span class="flex-1 text-left">
                            System Users
                        </span>

                        <svg
                            class="h-4 w-4 transition-transform duration-200"
                            :class="usersOpen ? 'rotate-180' : ''"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </button>


                    {{-- USERS SUBMENU --}}
                    <div
                        x-cloak
                        x-show="usersOpen"
                        x-transition
                        class="ml-6 space-y-1 border-l border-white/10 pl-3"
                    >

                        @if (Route::has('users.index'))
                            <a
                                href="{{ route('users.index') }}"
                                @click="sidebarOpen = false"
                                class="
                                    flex items-center gap-3 rounded-lg
                                    px-3 py-2.5 text-sm transition
                                    {{ request()->routeIs('users.index')
                                        ? 'bg-white/10 font-semibold text-white'
                                        : 'text-slate-400 hover:bg-white/5 hover:text-white'
                                    }}
                                "
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-600"></span>
                                All Users
                            </a>
                        @endif


                        @if (Route::has('users.create'))
                            <a
                                href="{{ route('users.create') }}"
                                @click="sidebarOpen = false"
                                class="
                                    flex items-center gap-3 rounded-lg
                                    px-3 py-2.5 text-sm transition
                                    {{ request()->routeIs('users.create')
                                        ? 'bg-white/10 font-semibold text-white'
                                        : 'text-slate-400 hover:bg-white/5 hover:text-white'
                                    }}
                                "
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-600"></span>
                                Create User
                            </a>
                        @endif


                        @if (Route::has('roles.index'))
                            <a
                                href="{{ route('roles.index') }}"
                                @click="sidebarOpen = false"
                                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-400 hover:bg-white/5 hover:text-white"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-600"></span>
                                Roles
                            </a>
                        @endif


                        @if (Route::has('permissions.index'))
                            <a
                                href="{{ route('permissions.index') }}"
                                @click="sidebarOpen = false"
                                class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm text-slate-400 hover:bg-white/5 hover:text-white"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-slate-600"></span>
                                Permissions
                            </a>
                        @endif

                    </div>
                </div>


                <div class="my-5 border-t border-white/10"></div>


                {{-- ACCOUNT --}}
                <div
                    class="
                        mb-3 px-3 text-[10px] font-bold
                        uppercase tracking-[0.18em]
                        text-slate-500
                    "
                >
                    Account
                </div>


                @if (Route::has('profile.show'))
                    <a
                        href="{{ route('profile.show') }}"
                        @click="sidebarOpen = false"
                        class="
                            mb-1 flex items-center gap-3
                            rounded-xl px-3 py-3
                            text-sm font-semibold transition
                            {{ request()->routeIs('profile.*')
                                ? 'bg-white text-slate-950 shadow-lg'
                                : 'text-slate-300 hover:bg-white/10 hover:text-white'
                            }}
                        "
                    >
                        <span
                            class="
                                flex h-9 w-9 shrink-0
                                items-center justify-center
                                rounded-lg bg-white/10
                            "
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M15 19a4 4 0 00-8 0M11 11a4 4 0 100-8 4 4 0 000 8zM20 21a8 8 0 10-16 0"
                                />
                            </svg>
                        </span>

                        My Profile
                    </a>
                @endif


                @if (Route::has('settings.index'))
                    <a
                        href="{{ route('settings.index') }}"
                        @click="sidebarOpen = false"
                        class="
                            mb-1 flex items-center gap-3
                            rounded-xl px-3 py-3
                            text-sm font-semibold
                            text-slate-300
                            hover:bg-white/10 hover:text-white
                        "
                    >
                        <span
                            class="
                                flex h-9 w-9 shrink-0
                                items-center justify-center
                                rounded-lg bg-white/10
                            "
                        >
                            ⚙
                        </span>

                        Settings
                    </a>
                @endif

            </nav>


            {{-- SIDEBAR USER --}}
            @php
                $currentUser = auth()->user();
            @endphp

            <div class="shrink-0 border-t border-white/10 p-3">
                <div class="flex items-center gap-3 rounded-xl bg-white/5 p-3">

                    <div
                        class="
                            flex h-10 w-10 shrink-0
                            items-center justify-center
                            overflow-hidden rounded-xl
                            bg-white text-sm font-extrabold
                            text-slate-950
                        "
                    >
                        @if ($currentUser?->profile_image_url)
                            <img
                                src="{{ $currentUser->profile_image_url }}"
                                alt="{{ $currentUser->display_name ?? 'User' }}"
                                class="h-full w-full object-cover"
                            >
                        @else
                            {{ $currentUser?->profile_initial ?? 'U' }}
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="truncate text-sm font-bold text-white">
                            {{ $currentUser?->display_name ?? 'User' }}
                        </div>

                        <div class="truncate text-xs text-slate-400">
                            {{ $currentUser?->email ?? '' }}
                        </div>
                    </div>

                </div>
            </div>

        </aside>


        {{-- =========================================================
             MAIN CONTENT AREA
             ========================================================= --}}
        <div class="min-h-screen min-w-0 lg:pl-72">


            {{-- HEADER --}}
            <header
                class="
                    sticky top-0 z-30
                    border-b border-slate-200
                    bg-white/95
                    shadow-sm
                    backdrop-blur
                "
            >
                <div
                    class="
                        flex h-20 items-center justify-between
                        px-4 sm:px-6 lg:px-8
                    "
                >

                    {{-- HEADER LEFT --}}
                    <div class="flex min-w-0 items-center gap-3">

                        <button
                            type="button"
                            @click="sidebarOpen = true"
                            class="
                                rounded-xl border border-slate-200
                                bg-white p-2.5 text-slate-600
                                shadow-sm hover:bg-slate-50
                                lg:hidden
                            "
                            aria-label="Open menu"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                            </svg>
                        </button>

                        <div class="min-w-0">
                            <div
                                class="
                                    truncate text-lg font-extrabold
                                    tracking-tight text-slate-900
                                    sm:text-xl
                                "
                            >
                                @hasSection('header')
                                    @yield('header')
                                @else
                                    @yield('title', 'Dashboard')
                                @endif
                            </div>

                            <div class="hidden text-xs text-slate-500 sm:block">
                                SAPTA Management System
                            </div>
                        </div>

                    </div>


                    {{-- HEADER RIGHT --}}
                    <div class="flex items-center gap-2 sm:gap-3">

                        {{-- NOTIFICATIONS --}}
                        <button
                            type="button"
                            class="
                                relative rounded-xl
                                border border-slate-200
                                bg-white p-2.5 text-slate-500
                                shadow-sm hover:bg-slate-50
                            "
                            aria-label="Notifications"
                        >
                            <svg
                                class="h-5 w-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.8"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                                />
                            </svg>

                            <span
                                class="
                                    absolute right-2 top-2
                                    h-2 w-2 rounded-full
                                    bg-red-500 ring-2 ring-white
                                "
                            ></span>
                        </button>


                        {{-- USER --}}
                        <div
                            class="relative"
                            @click.outside="profileOpen = false"
                        >

                            <button
                                type="button"
                                @click="profileOpen = !profileOpen"
                                class="
                                    flex items-center gap-2
                                    rounded-xl border border-slate-200
                                    bg-white px-2 py-1.5
                                    shadow-sm hover:bg-slate-50
                                "
                            >
                                <div
                                    class="
                                        flex h-9 w-9 shrink-0
                                        items-center justify-center
                                        overflow-hidden rounded-lg
                                        bg-slate-900 text-xs
                                        font-extrabold text-white
                                    "
                                >
                                    @if ($currentUser?->profile_image_url)
                                        <img
                                            src="{{ $currentUser->profile_image_url }}"
                                            alt="{{ $currentUser->display_name ?? 'User' }}"
                                            class="h-full w-full object-cover"
                                        >
                                    @else
                                        {{ $currentUser?->profile_initial ?? 'U' }}
                                    @endif
                                </div>

                                <div class="hidden text-left sm:block">
                                    <div class="max-w-32 truncate text-sm font-bold text-slate-800">
                                        {{ $currentUser?->display_name ?? 'User' }}
                                    </div>

                                    <div class="max-w-32 truncate text-[11px] text-slate-500">
                                        {{ $currentUser?->username ?? '' }}
                                    </div>
                                </div>

                                <svg
                                    class="hidden h-4 w-4 text-slate-400 sm:block"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    />
                                </svg>
                            </button>


                            {{-- USER DROPDOWN --}}
                            <div
                                x-cloak
                                x-show="profileOpen"
                                x-transition
                                class="
                                    absolute right-0 mt-2 w-72
                                    overflow-hidden rounded-2xl
                                    border border-slate-200
                                    bg-white shadow-2xl
                                "
                            >

                                <div class="border-b border-slate-100 bg-slate-50 p-4">
                                    <div class="flex items-center gap-3">

                                        <div
                                            class="
                                                flex h-12 w-12 shrink-0
                                                items-center justify-center
                                                overflow-hidden rounded-xl
                                                bg-slate-900 text-sm
                                                font-extrabold text-white
                                            "
                                        >
                                            @if ($currentUser?->profile_image_url)
                                                <img
                                                    src="{{ $currentUser->profile_image_url }}"
                                                    alt="{{ $currentUser->display_name ?? 'User' }}"
                                                    class="h-full w-full object-cover"
                                                >
                                            @else
                                                {{ $currentUser?->profile_initial ?? 'U' }}
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <div class="truncate font-bold text-slate-900">
                                                {{ $currentUser?->display_name ?? 'User' }}
                                            </div>

                                            <div class="truncate text-xs text-slate-500">
                                                {{ $currentUser?->email ?? '' }}
                                            </div>
                                        </div>

                                    </div>
                                </div>


                                @if (Route::has('profile.show'))
                                    <div class="p-2">

                                        <a
                                            href="{{ route('profile.show') }}"
                                            @click="profileOpen = false"
                                            class="
                                                flex items-center gap-3
                                                rounded-xl px-3 py-2.5
                                                text-sm font-semibold
                                                text-slate-700
                                                hover:bg-slate-100
                                            "
                                        >
                                            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                                                👤
                                            </span>

                                            My Profile
                                        </a>


                                        @if (Route::has('profile.edit'))
                                            <a
                                                href="{{ route('profile.edit') }}"
                                                @click="profileOpen = false"
                                                class="
                                                    flex items-center gap-3
                                                    rounded-xl px-3 py-2.5
                                                    text-sm font-semibold
                                                    text-slate-700
                                                    hover:bg-slate-100
                                                "
                                            >
                                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-slate-100">
                                                    ✎
                                                </span>

                                                Edit Profile
                                            </a>
                                        @endif

                                    </div>
                                @endif


                                @if (Route::has('logout'))
                                    <div class="border-t border-slate-100 p-2">

                                        <form
                                            method="POST"
                                            action="{{ route('logout') }}"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="
                                                    flex w-full items-center gap-3
                                                    rounded-xl px-3 py-2.5
                                                    text-left text-sm font-semibold
                                                    text-red-600
                                                    hover:bg-red-50
                                                "
                                            >
                                                <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50">
                                                    ↪
                                                </span>

                                                Sign Out
                                            </button>
                                        </form>

                                    </div>
                                @endif

                            </div>

                        </div>

                    </div>
                </div>
            </header>


            {{-- =====================================================
                 FLASH MESSAGES
                 ===================================================== --}}
            <div class="px-4 pt-4 sm:px-6 lg:px-8">

                @if (session('success'))
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        class="
                            mb-4 flex items-start gap-3
                            rounded-2xl border border-emerald-200
                            bg-emerald-50 p-4 text-emerald-800
                        "
                    >
                        <div class="flex-1">
                            <div class="font-bold">Success</div>

                            <div class="text-sm">
                                {{ session('success') }}
                            </div>
                        </div>

                        <button
                            type="button"
                            @click="show = false"
                            class="text-emerald-700 hover:text-emerald-900"
                        >
                            ✕
                        </button>
                    </div>
                @endif


                @if (session('error'))
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        class="
                            mb-4 flex items-start gap-3
                            rounded-2xl border border-red-200
                            bg-red-50 p-4 text-red-800
                        "
                    >
                        <div class="flex-1">
                            <div class="font-bold">Error</div>

                            <div class="text-sm">
                                {{ session('error') }}
                            </div>
                        </div>

                        <button
                            type="button"
                            @click="show = false"
                            class="text-red-700 hover:text-red-900"
                        >
                            ✕
                        </button>
                    </div>
                @endif


                @if (session('warning'))
                    <div
                        class="
                            mb-4 rounded-2xl
                            border border-amber-200
                            bg-amber-50 p-4 text-amber-800
                        "
                    >
                        <div class="font-bold">Warning</div>

                        <div class="text-sm">
                            {{ session('warning') }}
                        </div>
                    </div>
                @endif


                @if (session('info'))
                    <div
                        class="
                            mb-4 rounded-2xl
                            border border-blue-200
                            bg-blue-50 p-4 text-blue-800
                        "
                    >
                        <div class="font-bold">Information</div>

                        <div class="text-sm">
                            {{ session('info') }}
                        </div>
                    </div>
                @endif


                {{-- VALIDATION ERRORS --}}
                @if ($errors->any())
                    <div
                        class="
                            mb-4 rounded-2xl
                            border border-red-200
                            bg-red-50 p-4 text-red-800
                        "
                    >
                        <div class="mb-2 font-bold">
                            Please correct the following errors:
                        </div>

                        <ul class="list-disc space-y-1 pl-6 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

            </div>


            {{-- =====================================================
                 PAGE CONTENT
                 ===================================================== --}}
            <main
                class="
                    min-w-0
                    px-4 py-4
                    sm:px-6 sm:py-5
                    lg:px-8 lg:py-6
                "
            >
                @yield('content')
            </main>


            {{-- =====================================================
                 FOOTER
                 ===================================================== --}}
            <footer class="border-t border-slate-200 bg-white">

                <div
                    class="
                        flex flex-col items-center justify-between
                        gap-2 px-4 py-5
                        text-xs text-slate-500
                        sm:flex-row sm:px-6
                        lg:px-8
                    "
                >
                    <div>
                        &copy; {{ date('Y') }} SAPTA Management System.
                        All rights reserved.
                    </div>

                    <div>
                        System Administration
                    </div>
                </div>

            </footer>

        </div>
    </div>


    @stack('scripts')

</body>
</html>