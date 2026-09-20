@extends('layouts.sapta')

@section('title', 'Task Report')

@section('content')
<style>
    .report-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .report-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }
    .report-header p {
        color: #8898aa;
        margin: 4px 0 0;
        font-size: 14px;
    }
    .btn-back {
        background: #e8ecf1;
        color: #4a5a6f;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-back:hover {
        background: #d5d9e0;
        color: #1a1a2e;
        text-decoration: none;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 20px 24px;
        border: 1px solid #e8ecf1;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }
    .stat-card:nth-child(1)::before { background: linear-gradient(90deg, #1a5276, #2d8a9e); }
    .stat-card:nth-child(2)::before { background: linear-gradient(90deg, #6f42c1, #9b59b6); }
    .stat-card:nth-child(3)::before { background: linear-gradient(90deg, #ffc107, #fd7e14); }
    .stat-card:nth-child(4)::before { background: linear-gradient(90deg, #28a745, #20c997); }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0,0,0,0.08);
    }
    .stat-card .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .stat-card .stat-label {
        font-size: 13px;
        color: #8898aa;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .stat-card .stat-number {
        font-size: 32px;
        font-weight: 800;
        color: #1a1a2e;
        margin-top: 4px;
    }
    .stat-card .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .stat-card:nth-child(1) .stat-icon { background: #e8f4f8; color: #1a5276; }
    .stat-card:nth-child(2) .stat-icon { background: #e8d5f5; color: #6f42c1; }
    .stat-card:nth-child(3) .stat-icon { background: #fff3cd; color: #ffc107; }
    .stat-card:nth-child(4) .stat-icon { background: #d4edda; color: #28a745; }
    
    .badge-status {
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    .badge-status.todo { background: #e2e3e5; color: #383d41; }
    .badge-status.in_progress { background: #cce5ff; color: #004085; }
    .badge-status.review { background: #fff3cd; color: #856404; }
    .badge-status.done { background: #d4edda; color: #155724; }
    
    .table-container {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        margin-top: 20px;
    }
    .table-container .table-header {
        padding: 16px 24px;
        border-bottom: 1px solid #e8ecf1;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .table-container .table-header h5 {
        margin: 0;
        font-weight: 600;
        color: #1a1a2e;
        font-size: 16px;
    }
    .table-container .table-header span {
        color: #8898aa;
        font-size: 13px;
    }
    .table-container table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    .table-container thead {
        background: #f8f9fa;
        border-bottom: 2px solid #e8ecf1;
    }
    .table-container th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: #4a5a6f;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .table-container td {
        padding: 14px 16px;
        border-bottom: 1px solid #f0f2f5;
        color: #1a1a2e;
        vertical-align: middle;
    }
    .table-container tbody tr:hover {
        background: #f8f9fa;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #8898aa;
    }
    .empty-state i {
        font-size: 48px;
        color: #d5d9e0;
        display: block;
        margin-bottom: 16px;
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr 1fr; }
        .report-header { flex-direction: column; text-align: center; }
        .table-container { overflow-x: auto; }
        .table-container table { min-width: 700px; }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
        .stat-card .stat-number { font-size: 24px; }
    }
</style>

<div class="report-header">
    <div>
        <h1 data-en="Task Report" data-sw="Ripoti ya Kazi">Task Report</h1>
        <p data-en="View task statistics and completion" data-sw="Tazama takwimu na ukamilishaji wa kazi">View task statistics and completion</p>
    </div>
    <a href="{{ route('reports.index') }}" class="btn-back">
        <i class="fas fa-arrow-left"></i> <span data-en="Back" data-sw="Rudi">Back</span>
    </a>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="Total Tasks" data-sw="Jumla ya Kazi">Total Tasks</div>
                <div class="stat-number">{{ $totalTasks ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-tasks"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="To Do" data-sw="Kufanya">To Do</div>
                <div class="stat-number" style="color:#6f42c1;">{{ $todoTasks ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-circle"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="In Progress" data-sw="Inaendelea">In Progress</div>
                <div class="stat-number" style="color:#ffc107;">{{ $inProgressTasks ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-spinner"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <div class="stat-label" data-en="Completed" data-sw="Imekamilika">Completed</div>
                <div class="stat-number" style="color:#28a745;">{{ $doneTasks ?? 0 }}</div>
            </div>
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="table-container">
    <div class="table-header">
        <h5 data-en="Task List" data-sw="Orodha ya Kazi">Task List</h5>
        <span>{{ isset($tasks) ? $tasks->count() : 0 }} <span data-en="tasks" data-sw="kazi">tasks</span></span>
    </div>
    <table>
        <thead>
            <tr>
                <th data-en="ID" data-sw="Nambari">ID</th>
                <th data-en="Title" data-sw="Jina">Title</th>
                <th data-en="Project" data-sw="Mradi">Project</th>
                <th data-en="Status" data-sw="Hali">Status</th>
                <th data-en="Progress" data-sw="Maendeleo">Progress</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks ?? [] as $task)
            <tr>
                <td><strong>{{ $task->id }}</strong></td>
                <td>{{ $task->title }}</td>
                <td>{{ $task->project->name ?? 'N/A' }}</td>
                <td>
                    @php
                        $status = $task->status ?? 'todo';
                        $statusClass = match($status) {
                            'todo' => 'todo',
                            'in_progress' => 'in_progress',
                            'review' => 'review',
                            'done' => 'done',
                            default => 'todo'
                        };
                        $statusText = match($status) {
                            'todo' => 'To Do',
                            'in_progress' => 'In Progress',
                            'review' => 'Review',
                            'done' => 'Done',
                            default => ucfirst($status)
                        };
                    @endphp
                    <span class="badge-status {{ $statusClass }}">{{ $statusText }}</span>
                </td>
                <td>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div style="flex:1;height:6px;background:#e8ecf1;border-radius:3px;overflow:hidden;min-width:60px;">
                            <div style="height:100%;width:{{ $task->progress }}%;background:linear-gradient(90deg, #1a5276, #2d8a9e);border-radius:3px;"></div>
                        </div>
                        <span style="font-size:12px;font-weight:600;min-width:35px;">{{ $task->progress }}%</span>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">
                    <div class="empty-state">
                        <i class="fas fa-tasks"></i>
                        <p data-en="No tasks found" data-sw="Hakuna kazi">No tasks found</p>
                        <p style="font-size:13px;color:#94a3b8;margin-top:8px;" data-en="Create your first task" data-sw="Tengeneza kazi yako ya kwanza">Create your first task</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if(isset($tasks) && method_exists($tasks, 'links'))
    <div style="padding:16px 24px;border-top:1px solid #e8ecf1;">
        {{ $tasks->links() }}
    </div>
    @endif
</div>
@endsection

