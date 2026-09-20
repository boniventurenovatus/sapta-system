@props([
    'icon' => 'fa-inbox',
    'title' => 'Hakuna Data',
    'message' => 'Hakuna kitu hapa bado.',
    'actionLabel' => null,
    'actionUrl' => null,
])

<div class="text-center py-16 px-6">
    <div class="w-20 h-20 mx-auto bg-slate-100 rounded-full flex items-center justify-center mb-4">
        <i class="fas {{ $icon }} text-3xl text-slate-400"></i>
    </div>
    <h3 class="text-lg font-extrabold text-slate-900 mb-2">{{ $title }}</h3>
    <p class="text-sm text-slate-500 max-w-md mx-auto">{{ $message }}</p>
    @if($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="inline-block mt-6 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition">
            {{ $actionLabel }}
        </a>
    @endif
</div>