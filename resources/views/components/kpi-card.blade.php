@props([
    'label' => 'Label',
    'value' => '0',
    'icon' => 'fa-chart-line',
    'color' => 'blue',
    'href' => null,
])

@php
    $colorClasses = [
        'blue' => 'bg-blue-50 text-blue-600',
        'green' => 'bg-emerald-50 text-emerald-600',
        'yellow' => 'bg-amber-50 text-amber-600',
        'red' => 'bg-red-50 text-red-600',
        'purple' => 'bg-purple-50 text-purple-600',
        'indigo' => 'bg-indigo-50 text-indigo-600',
    ];
    $colorClass = $colorClasses[$color] ?? $colorClasses['blue'];
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif class="block bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <div class="text-xs text-slate-500 font-extrabold uppercase tracking-wider">{{ $label }}</div>
            <div class="text-3xl font-extrabold text-slate-900 mt-2">{{ $value }}</div>
        </div>
        <div class="w-12 h-12 rounded-xl {{ $colorClass }} flex items-center justify-center flex-shrink-0">
            <i class="fas {{ $icon }} text-lg"></i>
        </div>
    </div>
</{{ $tag }}>