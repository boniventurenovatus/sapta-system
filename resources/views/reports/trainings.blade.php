@extends('layouts.reports')

@section('title', 'Trainings Report')
@section('report-title', 'Trainings Report')
@section('report-subtitle', 'All training programs')
@section('export-route', route('reports.export.trainings'))

@section('report-content')

<div class="rp-stats">
    <div class="rp-stat">
        <p class="rp-stat-label">Total</p>
        <p class="rp-stat-value">{{ $stats['total'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-graduation-cap"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Planned</p>
        <p class="rp-stat-value">{{ $stats['planned'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-calendar"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Ongoing</p>
        <p class="rp-stat-value">{{ $stats['ongoing'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-spinner"></i></div>
    </div>
    <div class="rp-stat">
        <p class="rp-stat-label">Completed</p>
        <p class="rp-stat-value">{{ $stats['completed'] ?? 0 }}</p>
        <div class="rp-stat-icon"><i class="fas fa-circle-check"></i></div>
    </div>
</div>

<div class="rp-card">
    <div class="rp-card-head">
        <h2><i class="fas fa-list"></i> Trainings ({{ $trainings->count() ?? 0 }})</h2>
    </div>
    @if(($trainings->count() ?? 0) > 0)
        <div class="rp-table-wrap">
            <table class="rp-table">
                <thead>
                    <tr>
                        <th>Training #</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Trainer</th>
                        <th>Start Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trainings as $t)
                    <tr>
                        <td><code>{{ $t->training_number ?? $t->id }}</code></td>
                        <td><strong>{{ $t->title ?? '—' }}</strong></td>
                        <td>{{ ucfirst(str_replace('_', ' ', $t->category ?? '—')) }}</td>
                        <td>{{ $t->trainer_name ?? '—' }}</td>
                        <td>{{ isset($t->start_date) ? \Carbon\Carbon::parse($t->start_date)->format('M d, Y') : '—' }}</td>
                        <td><span class="rp-badge rp-badge-{{ $t->status ?? 'planned' }}">{{ ucfirst($t->status ?? 'planned') }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="rp-empty">
            <i class="fas fa-inbox"></i>
            <p>No trainings found.</p>
        </div>
    @endif
</div>

@endsection