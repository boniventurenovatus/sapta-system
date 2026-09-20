@props([
    'name' => 'members',
    'users' => [],
    'selected' => [],
    'label' => 'Select Users',
    'placeholder' => 'Search users...',
    'color' => 'blue',
])

@php
    $colors = [
        'blue' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'border' => 'border-blue-500', 'ring' => 'focus:ring-blue-500', 'badge' => 'bg-blue-600'],
        'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'border' => 'border-purple-500', 'ring' => 'focus:ring-purple-500', 'badge' => 'bg-purple-600'],
        'green' => ['bg' => 'bg-green-100', 'text' => 'text-green-600', 'border' => 'border-green-500', 'ring' => 'focus:ring-green-500', 'badge' => 'bg-green-600'],
    ];
    $c = $colors[$color] ?? $colors['blue'];
    $selectedIds = is_array($selected) ? $selected : [];
@endphp

<div x-data="{
    open: false,
    search: '',
    selected: {{ json_encode($selectedIds) }},
    users: {{ json_encode($users->map(fn($u) => ['id' => $u->id, 'username' => $u->username, 'email' => $u->email])->values()->toArray()) }},
    get filteredUsers() {
        if (!this.search) return this.users;
        const s = this.search.toLowerCase();
        return this.users.filter(u => u.username.toLowerCase().includes(s) || u.email.toLowerCase().includes(s));
    },
    get selectedUsers() {
        return this.users.filter(u => this.selected.includes(u.id));
    },
    toggle(id) {
        if (this.selected.includes(id)) {
            this.selected = this.selected.filter(i => i !== id);
        } else {
            this.selected.push(id);
        }
    },
    isSelected(id) {
        return this.selected.includes(id);
    },
    selectAll() {
        this.selected = this.filteredUsers.map(u => u.id);
    },
    clearAll() {
        this.selected = [];
    },
    getInitials(username) {
        return username.substring(0, 2).toUpperCase();
    }
}" class="relative">

    <label class="block text-sm font-bold text-slate-700 mb-2">
        {{ $label }}
        <span x-show="selected.length > 0" class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold text-white {{ $c['badge'] }}" x-text="selected.length"></span>
    </label>

    {{-- SELECTED PREVIEW --}}
    <div x-show="selected.length > 0" class="mb-3 flex flex-wrap gap-2">
        <template x-for="user in selectedUsers" :key="user.id">
            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold border border-slate-200">
                <div class="w-5 h-5 rounded-full {{ $c['bg'] }} {{ $c['text'] }} flex items-center justify-center text-[10px] font-bold" x-text="getInitials(user.username)"></div>
                <span x-text="user.username"></span>
                <button type="button" @click="toggle(user.id)" class="ml-0.5 text-slate-400 hover:text-red-600">
                    <i class="fas fa-times text-[10px]"></i>
                </button>
            </div>
        </template>
    </div>

    {{-- DROPDOWN TRIGGER --}}
    <button type="button" @click="open = !open"
            class="w-full px-4 py-3 border border-slate-300 rounded-xl text-left flex items-center justify-between hover:border-slate-400 transition {{ $c['ring'] }} focus:ring-2 focus:outline-none">
        <span x-show="selected.length === 0" class="text-slate-400">{{ $placeholder }}</span>
        <span x-show="selected.length > 0" class="text-slate-700 font-semibold">
            <span x-text="selected.length"></span> user(s) selected
        </span>
        <i class="fas fa-chevron-down text-slate-400 transition" :class="{ 'rotate-180': open }"></i>
    </button>

    {{-- DROPDOWN MENU --}}
    <div x-show="open" @click.outside="open = false" x-transition
         class="absolute z-50 mt-2 w-full bg-white rounded-xl border border-slate-200 shadow-xl max-h-80 overflow-hidden">

        {{-- SEARCH + ACTIONS --}}
        <div class="p-3 border-b border-slate-100 bg-slate-50">
            <div class="relative mb-2">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" x-model="search" placeholder="Search by name or email..."
                       class="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 {{ $c['ring'] }} focus:border-transparent outline-none">
            </div>
            <div class="flex gap-2">
                <button type="button" @click="selectAll" 
                        class="flex-1 text-xs font-bold px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                    <i class="fas fa-check-double"></i> Select All
                </button>
                <button type="button" @click="clearAll" 
                        class="flex-1 text-xs font-bold px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>

        {{-- USER LIST --}}
        <div class="max-h-60 overflow-y-auto">
            <template x-for="user in filteredUsers" :key="user.id">
                <button type="button" @click="toggle(user.id)"
                        class="w-full px-3 py-2.5 flex items-center gap-3 hover:bg-slate-50 transition text-left"
                        :class="{ 'bg-{{ $c['bg'] }}': isSelected(user.id) }">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                         :class="isSelected(user.id) ? '{{ $c['bg'] }} {{ $c['text'] }}' : 'bg-slate-100 text-slate-500'"
                         x-text="getInitials(user.username)"></div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-slate-900 text-sm" x-text="user.username"></div>
                        <div class="text-xs text-slate-500 truncate" x-text="user.email"></div>
                    </div>
                    <div class="w-5 h-5 rounded border-2 flex items-center justify-center flex-shrink-0"
                         :class="isSelected(user.id) ? '{{ $c['border'] }} {{ $c['badge'] }}' : 'border-slate-300'">
                        <i x-show="isSelected(user.id)" class="fas fa-check text-white text-[10px]"></i>
                    </div>
                </button>
            </template>

            {{-- NO RESULTS --}}
            <template x-if="filteredUsers.length === 0">
                <div class="p-6 text-center text-slate-400 text-sm">
                    <i class="fas fa-search text-2xl mb-2"></i>
                    <div>No users found</div>
                </div>
            </template>
        </div>

        {{-- FOOTER --}}
        <div class="p-3 border-t border-slate-100 bg-slate-50 flex justify-between items-center">
            <span class="text-xs text-slate-500">
                <span x-text="selected.length"></span> of <span x-text="users.length"></span> selected
            </span>
            <button type="button" @click="open = false" 
                    class="text-xs font-bold px-3 py-1.5 rounded-lg text-white {{ $c['badge'] }} hover:opacity-90 transition">
                Done
            </button>
        </div>
    </div>

    {{-- HIDDEN INPUTS --}}
    <template x-for="id in selected" :key="id">
        <input type="hidden" name="{{ $name }}[]" :value="id">
    </template>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>