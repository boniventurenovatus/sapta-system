@props([
    'color' => 'slate',
    'label' => 'Label',
])

@php
    $colors = [
        'slate' => 'bg-slate-100 text-slate-700',
        'blue' => 'bg-blue-100 text-blue-700',
        'green' => 'bg-emerald-100 text-emerald-700',
        'yellow' => 'bg-amber-100 text-amber-700',
        'red' => 'bg-red-100 text-red-700',
        'purple' => 'bg-purple-100 text-purple-700',
    ];
    $colorClass = $colors[$color] ?? $colors['slate'];
@endphp

<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $colorClass }}">
    {{ $label }}
</span>