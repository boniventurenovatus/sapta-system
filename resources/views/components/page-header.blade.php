@props([
    'title' => 'Page Title',
    'subtitle' => null,
    'icon' => 'fa-gauge-high',
    'gradient' => 'blue',
])

@php
    $gradients = [
        'blue'    => 'linear-gradient(135deg, #2563eb 0%, #4f46e5 100%)',
        'green'   => 'linear-gradient(135deg, #059669 0%, #14b8a6 100%)',
        'amber'   => 'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)',
        'red'     => 'linear-gradient(135deg, #dc2626 0%, #ef4444 100%)',
        'purple'  => 'linear-gradient(135deg, #7c3aed 0%, #a855f7 100%)',
        'slate'   => 'linear-gradient(135deg, #475569 0%, #64748b 100%)',
        'cyan'    => 'linear-gradient(135deg, #0891b2 0%, #06b6d4 100%)',
    ];
    $bgStyle = $gradients[$gradient] ?? $gradients['blue'];
@endphp

<div style="background: {{ $bgStyle }}; color: #fff; padding: 2rem; border-radius: 1.25rem; margin-bottom: 1.5rem; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15);">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 3.5rem; height: 3.5rem; background: rgba(255,255,255,0.2); border-radius: 1rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="fas {{ $icon }}" style="font-size: 1.5rem; color: #fff;"></i>
        </div>
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0; color: #fff; line-height: 1.2;">{{ $title }}</h1>
            @if($subtitle)
                <p style="margin: 0.35rem 0 0; color: rgba(255,255,255,0.9); font-size: 0.9rem; font-weight: 500;">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</div>