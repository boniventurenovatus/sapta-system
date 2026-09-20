@props([
    'title' => null,
    'icon' => null,
    'action' => null,
    'actionUrl' => null,
])

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    @if($title)
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                @if($icon)
                    <i class="fas {{ $icon }} text-blue-500"></i>
                @endif
                {{ $title }}
            </h3>
            @if($action && $actionUrl)
                <a href="{{ $actionUrl }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">{{ $action }} →</a>
            @endif
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>