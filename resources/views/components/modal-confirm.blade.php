@props([
    'id' => 'confirm-modal',
    'title' => 'Confirm Action',
    'message' => 'Are you sure you want to proceed?',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'color' => 'red',
])

@php
    $colors = [
        'red' => ['bg' => 'bg-red-600 hover:bg-red-700', 'icon' => 'fa-exclamation-triangle', 'iconBg' => 'bg-red-100 text-red-600'],
        'green' => ['bg' => 'bg-emerald-600 hover:bg-emerald-700', 'icon' => 'fa-check-circle', 'iconBg' => 'bg-emerald-100 text-emerald-600'],
        'blue' => ['bg' => 'bg-blue-600 hover:bg-blue-700', 'icon' => 'fa-info-circle', 'iconBg' => 'bg-blue-100 text-blue-600'],
        'yellow' => ['bg' => 'bg-amber-600 hover:bg-amber-700', 'icon' => 'fa-exclamation-circle', 'iconBg' => 'bg-amber-100 text-amber-600'],
    ];
    $c = $colors[$color] ?? $colors['red'];
@endphp

<div id="{{ $id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6" style="animation: modalIn 0.2s ease-out;">
        <div class="text-center">
            <div class="w-16 h-16 {{ $c['iconBg'] }} rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas {{ $c['icon'] }} text-2xl"></i>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900 mb-2">{{ $title }}</h3>
            <p class="text-sm text-slate-600 mb-6">{{ $message }}</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('{{ $id }}')" class="flex-1 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
                    {{ $cancelText }}
                </button>
                <button type="button" id="{{ $id }}-confirm" class="flex-1 px-5 py-2.5 {{ $c['bg'] }} text-white font-bold rounded-xl transition">
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(-10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
</style>