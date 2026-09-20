@props([
    'color' => 'blue',
    'icon' => null,
    'href' => null,
    'type' => 'button',
])

@php
    $colors = [
        'blue' => 'bg-blue-600 hover:bg-blue-700 text-white',
        'green' => 'bg-emerald-600 hover:bg-emerald-700 text-white',
        'red' => 'bg-red-600 hover:bg-red-700 text-white',
        'slate' => 'bg-slate-100 hover:bg-slate-200 text-slate-700',
        'outline' => 'bg-white hover:bg-slate-50 text-slate-700 border border-slate-300',
    ];
    $colorClass = $colors[$color] ?? $colors['blue'];
    $tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }} 
    @if($href) href="{{ $href }}" @else type="{{ $type }}" @endif
    {{ $attributes->merge(['class' => "inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-sm transition $colorClass"]) }}
>
    @if($icon)
        <i class="fas {{ $icon }}"></i>
    @endif
    {{ $slot }}
</{{ $tag }}>