@extends('layouts.sapta')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
<style>
    .st-page { padding: 1.5rem; max-width: 1200px; margin: 0 auto; }
    .st-head { display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; }
    .st-head-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 8px 20px rgba(37,99,235,0.25); flex-shrink: 0; }
    .st-head h1 { font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem; }
    .st-head p { color: #64748b; margin: 0; font-size: 0.9rem; }
    .st-tabs { display: flex; gap: 0.5rem; margin-bottom: 1.5rem; flex-wrap: wrap; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; }
    .st-tab { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.6rem 1rem; border-radius: 0.5rem 0.5rem 0 0; font-size: 0.85rem; font-weight: 700; color: #64748b; background: transparent; border: none; cursor: pointer; transition: all 0.2s; border-bottom: 2px solid transparent; }
    .st-tab:hover { color: #2563eb; background: #f8fafc; }
    .st-tab.active { color: #2563eb; border-bottom-color: #2563eb; background: #eff6ff; }
    .st-card { background: #fff; border-radius: 1rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem; }
    .st-card-head { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; background: #fafbfc; }
    .st-card-head h2 { font-size: 0.95rem; font-weight: 800; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 0.5rem; }
    .st-card-head h2 i { color: #2563eb; }
    .st-card-body { padding: 1.5rem; }
    .st-group { margin-bottom: 1.25rem; }
    .st-group label { display: block; font-size: 0.85rem; font-weight: 700; color: #334155; margin-bottom: 0.5rem; }
    .st-input { width: 100%; padding: 0.65rem 0.875rem; border: 1.5px solid #e2e8f0; border-radius: 0.5rem; font-size: 0.9rem; color: #1e293b; background: #fff; transition: all 0.2s; font-family: inherit; }
    .st-input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .st-checkbox { display: flex; align-items: center; gap: 0.5rem; padding: 0.65rem 0.875rem; background: #f8fafc; border-radius: 0.5rem; border: 1.5px solid #e2e8f0; cursor: pointer; }
    .st-checkbox input[type=checkbox] { width: 1.1rem; height: 1.1rem; accent-color: #2563eb; }
    .st-actions { display: flex; gap: 0.75rem; padding: 1.25rem 1.5rem; background: #fafbfc; border-top: 1px solid #f1f5f9; position: sticky; bottom: 0; }
    .st-btn { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.7rem 1.5rem; border-radius: 0.5rem; font-weight: 700; font-size: 0.9rem; border: none; cursor: pointer; text-decoration: none; transition: all 0.2s; }
    .st-btn-primary { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; box-shadow: 0 4px 12px rgba(37,99,235,0.25); }
    .st-btn-primary:hover { transform: translateY(-1px); color: #fff; }
    .st-alert { padding: 0.875rem 1.25rem; background: #dcfce7; color: #15803d; border-radius: 0.5rem; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 600; }
    .st-pane { display: none; }
    .st-pane.active { display: block; }
</style>

<div class="st-page">
    <div class="st-head">
        <div class="st-head-icon"><i class="fas fa-gear"></i></div>
        <div>
            <h1>Settings</h1>
            <p>Manage system settings and configuration.</p>
        </div>
    </div>

    

    <div class="st-tabs">
        <button class="st-tab active" onclick="showTab('general', this)"><i class="fas fa-building"></i> General</button>
        <button class="st-tab" onclick="showTab('system', this)"><i class="fas fa-sliders"></i> System</button>
        <button class="st-tab" onclick="showTab('security', this)"><i class="fas fa-shield-halved"></i> Security</button>
        <button class="st-tab" onclick="showTab('notifications', this)"><i class="fas fa-bell"></i> Notifications</button>
    </div>

    <form action="{{ route('settings.update') }}" method="POST">
        @csrf @method('PUT')

        <div id="pane-general" class="st-pane active">
            <div class="st-card">
                <div class="st-card-head"><h2><i class="fas fa-building"></i> General Settings</h2></div>
                <div class="st-card-body">
                    @foreach($groups['general'] as $s)
                        <div class="st-group">
                            <label for="{{ $s->key }}">{{ $s->label ?? $s->key }}</label>
                            @if($s->type === 'text')
                                <textarea id="{{ $s->key }}" name="{{ $s->key }}" class="st-input" rows="2">{{ $s->value }}</textarea>
                            @else
                                <input type="{{ $s->type }}" id="{{ $s->key }}" name="{{ $s->key }}" class="st-input" value="{{ $s->value }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="pane-system" class="st-pane">
            <div class="st-card">
                <div class="st-card-head"><h2><i class="fas fa-sliders"></i> System Settings</h2></div>
                <div class="st-card-body">
                    @foreach($groups['system'] as $s)
                        <div class="st-group">
                            <label for="{{ $s->key }}">{{ $s->label ?? $s->key }}</label>
                            @if($s->type === 'text')
                                <textarea id="{{ $s->key }}" name="{{ $s->key }}" class="st-input" rows="2">{{ $s->value }}</textarea>
                            @else
                                <input type="{{ $s->type }}" id="{{ $s->key }}" name="{{ $s->key }}" class="st-input" value="{{ $s->value }}">
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="pane-security" class="st-pane">
            <div class="st-card">
                <div class="st-card-head"><h2><i class="fas fa-shield-halved"></i> Security Settings</h2></div>
                <div class="st-card-body">
                    @foreach($groups['security'] as $s)
                        <div class="st-group">
                            <label for="{{ $s->key }}">{{ $s->label ?? $s->key }}</label>
                            <input type="{{ $s->type }}" id="{{ $s->key }}" name="{{ $s->key }}" class="st-input" value="{{ $s->value }}">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="pane-notifications" class="st-pane">
            <div class="st-card">
                <div class="st-card-head"><h2><i class="fas fa-bell"></i> Notification Settings</h2></div>
                <div class="st-card-body">
                    @foreach($groups['notifications'] as $s)
                        <div class="st-group">
                            <label class="st-checkbox">
                                <input type="hidden" name="{{ $s->key }}" value="0">
                                <input type="checkbox" name="{{ $s->key }}" value="1" @checked($s->value == '1')>
                                <span>{{ $s->label ?? $s->key }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="st-actions">
            <button type="submit" class="st-btn st-btn-primary"><i class="fas fa-save"></i> Save Settings</button>
        </div>
    </form>
</div>

<script>
function showTab(name, btn) {
    document.querySelectorAll('.st-pane').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.st-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('pane-' + name).classList.add('active');
    btn.classList.add('active');
}
</script>
@endsection

