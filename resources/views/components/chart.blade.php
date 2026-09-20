@props([
    'id' => 'chart-' . uniqid(),
    'type' => 'bar',
    'title' => 'Chart',
    'icon' => 'fa-chart-bar',
    'height' => '300',
    'labels' => [],
    'datasets' => [],
    'colors' => ['#2563eb'],
])

<div class="bg-white rounded-2xl border border-slate-200 p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
            <i class="fas {{ $icon }} text-blue-500"></i>
            {{ $title }}
        </h3>
    </div>
    <div style="position:relative; height:{{ $height }}px;">
        <canvas id="{{ $id }}"></canvas>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $id }}');
    if (!ctx) return;

    new Chart(ctx, {
        type: '{{ $type }}',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: {!! json_encode($datasets) !!}
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: {{ count($datasets) > 1 ? 'true' : 'false' }},
                    position: 'bottom',
                    labels: { font: { size: 11, weight: '600' }, padding: 15, usePointStyle: true }
                },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 12, weight: '700' },
                    bodyFont: { size: 11 }
                }
            },
            scales: '{{ $type }}' === 'pie' || '{{ $type }}' === 'doughnut' ? {} : {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 10, weight: '600' }, color: '#64748b' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 10, weight: '600' }, color: '#64748b' }
                }
            }
        }
    });
});
</script>
@endpush