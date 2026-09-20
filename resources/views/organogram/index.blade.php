@extends('layouts.sapta')

@section('title', 'Organogram')
@section('page-title', 'Organogram')

@push('styles')
<style>
    .og-page { padding: 2rem 1.5rem; background: linear-gradient(135deg, #f0f4ff 0%, #f8fafc 100%); min-height: 100vh; }
    .og-container { max-width: 1400px; margin: 0 auto; }

    .og-head { text-align: center; margin-bottom: 3rem; }
    .og-head-icon { width: 4.5rem; height: 4.5rem; border-radius: 1.25rem; background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 2rem; box-shadow: 0 16px 32px rgba(37,99,235,0.3); margin: 0 auto 1.25rem; }
    .og-head h1 { font-size: 2.25rem; font-weight: 800; color: #0f172a; margin: 0 0 0.5rem; letter-spacing: -0.02em; }
    .og-head p { color: #64748b; margin: 0; font-size: 1rem; }

    /* SECTION */
    .og-section { margin-bottom: 2.5rem; }
    .og-section-title { display: flex; align-items: center; gap: 0.75rem; font-size: 0.85rem; font-weight: 800; color: #475569; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1.25rem; }
    .og-section-title::before { content: ''; width: 4px; height: 1.25rem; background: #2563eb; border-radius: 999px; }
    .og-section-title::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

    /* ===== TOP CARDS (Board & CEO) ===== */
    .og-top-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; }
    .og-top-card { background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%); border-radius: 1.5rem; padding: 2rem; color: #fff; position: relative; overflow: hidden; box-shadow: 0 20px 40px rgba(30,64,175,0.25); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; }
    .og-top-card.ceo { background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%); box-shadow: 0 20px 40px rgba(124,58,237,0.25); }
    .og-top-card:hover { transform: translateY(-8px) scale(1.02); box-shadow: 0 30px 60px rgba(30,64,175,0.35); }
    .og-top-card.ceo:hover { box-shadow: 0 30px 60px rgba(124,58,237,0.35); }
    .og-top-card::before { content: ''; position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); pointer-events: none; }
    .og-top-card::after { content: ''; position: absolute; top: 1rem; right: 1rem; width: 60px; height: 60px; border-radius: 50%; background: rgba(255,255,255,0.08); }

    .og-top-icon { width: 4rem; height: 4rem; border-radius: 1rem; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin-bottom: 1.25rem; position: relative; z-index: 1; transition: transform 0.4s; }
    .og-top-card:hover .og-top-icon { transform: rotate(-8deg) scale(1.1); }
    .og-top-icon .fa-crown { color: #fbbf24; }

    .og-top-card h3 { font-size: 1.25rem; font-weight: 800; margin: 0 0 0.5rem; color: #fff; position: relative; z-index: 1; letter-spacing: -0.02em; }
    .og-top-badge { display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.3rem 0.75rem; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border-radius: 999px; font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; position: relative; z-index: 1; }
    .og-top-positions { margin-top: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem; position: relative; z-index: 1; }
    .og-top-pos { background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); padding: 0.6rem 0.875rem; border-radius: 0.625rem; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: background 0.2s; }
    .og-top-pos:hover { background: rgba(255,255,255,0.25); }

    /* ===== DEPARTMENT CARDS ===== */
    .og-dept-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.75rem; }

    .og-dept-card { background: #fff; border-radius: 1.5rem; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.06); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); position: relative; border: 1px solid #e2e8f0; }
    .og-dept-card:hover { transform: translateY(-8px); box-shadow: 0 24px 48px rgba(0,0,0,0.12); border-color: transparent; }

    .og-dept-header { padding: 1.75rem 1.5rem 1.5rem; position: relative; overflow: hidden; }
    .og-dept-card.d-admin .og-dept-header { background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); }
    .og-dept-card.d-prog .og-dept-header { background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); }
    .og-dept-card.d-meal .og-dept-header { background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); }
    .og-dept-card.d-ict .og-dept-header { background: linear-gradient(135deg, #cffafe 0%, #a5f3fc 100%); }

    .og-dept-header::before { content: ''; position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; border-radius: 50%; background: rgba(255,255,255,0.4); }
    .og-dept-header::after { content: ''; position: absolute; bottom: -40px; right: 20px; width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.3); }

    .og-dept-icon { width: 3.5rem; height: 3.5rem; border-radius: 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1rem; position: relative; z-index: 1; background: #fff; box-shadow: 0 8px 20px rgba(0,0,0,0.08); transition: transform 0.4s; }
    .og-dept-card:hover .og-dept-icon { transform: scale(1.1) rotate(5deg); }
    .og-dept-card.d-admin .og-dept-icon { color: #d97706; }
    .og-dept-card.d-prog .og-dept-icon { color: #059669; }
    .og-dept-card.d-meal .og-dept-icon { color: #7c3aed; }
    .og-dept-card.d-ict .og-dept-icon { color: #0891b2; }

    .og-dept-title { font-size: 1.05rem; font-weight: 800; color: #0f172a; margin: 0 0 0.35rem; line-height: 1.3; position: relative; z-index: 1; letter-spacing: -0.02em; }
    .og-dept-meta { font-size: 0.75rem; color: #475569; font-weight: 700; letter-spacing: 0.05em; position: relative; z-index: 1; display: flex; align-items: center; gap: 0.5rem; }
    .og-dept-count { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.6rem; background: rgba(255,255,255,0.7); border-radius: 999px; font-size: 0.7rem; font-weight: 800; }

    .og-dept-body { padding: 1.25rem 1.5rem 1.5rem; }
    .og-dept-body-label { font-size: 0.7rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.875rem; display: flex; align-items: center; gap: 0.5rem; }
    .og-dept-body-label::after { content: ''; flex: 1; height: 1px; background: #f1f5f9; }

    .og-pos-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .og-pos-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 0.875rem; background: #f8fafc; border-radius: 0.625rem; text-decoration: none; color: #334155; transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1); border-left: 3px solid transparent; }
    .og-pos-item:hover { background: #eff6ff; color: #1e40af; border-left-color: #2563eb; transform: translateX(4px); }
    .og-pos-item i { color: #94a3b8; font-size: 0.85rem; flex-shrink: 0; transition: color 0.2s; }
    .og-pos-item:hover i { color: #2563eb; }
    .og-pos-title { flex: 1; font-size: 0.85rem; font-weight: 600; line-height: 1.3; }
    .og-pos-code { font-size: 0.68rem; color: #94a3b8; font-weight: 800; background: #fff; padding: 0.2rem 0.55rem; border-radius: 0.375rem; letter-spacing: 0.03em; transition: all 0.2s; }
    .og-pos-item:hover .og-pos-code { background: #dbeafe; color: #1e40af; }

    .og-pos-item.lead { background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-left-color: #2563eb; }
    .og-pos-item.lead i { color: #2563eb; }
    .og-pos-item.lead .og-pos-title { color: #1e40af; font-weight: 800; }
    .og-pos-item.lead .og-pos-code { background: #2563eb; color: #fff; }

    /* FADE-IN ANIMATION */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .og-dept-card, .og-top-card { animation: fadeInUp 0.6s ease-out backwards; }
    .og-dept-card:nth-child(1) { animation-delay: 0.1s; }
    .og-dept-card:nth-child(2) { animation-delay: 0.2s; }
    .og-dept-card:nth-child(3) { animation-delay: 0.3s; }
    .og-dept-card:nth-child(4) { animation-delay: 0.4s; }

    @media (max-width: 768px) {
        .og-head h1 { font-size: 1.6rem; }
        .og-page { padding: 1.5rem 1rem; }
        .og-top-card { padding: 1.5rem; }
    }
</style>
@endpush

@section('content')
<div class="og-page">
    <div class="og-container">

        {{-- HEADER --}}
        <div class="og-head">
            <div class="og-head-icon"><i class="fas fa-sitemap"></i></div>
            <h1>SAPTA Organogram</h1>
            <p>Organizational structure ? Board, CEO, Departments & Positions</p>
        </div>

        {{-- TOP: BOARD & CEO --}}
        <div class="og-section">
            <div class="og-section-title">Leadership</div>
            <div class="og-top-grid">
                @if($bod)
                    <div class="og-top-card">
                        <div class="og-top-icon"><i class="fas fa-crown"></i></div>
                        <h3>{{ $bod->name }}</h3>
                        <span class="og-top-badge"><i class="fas fa-hashtag"></i> {{ $bod->code }}</span>
                        @if($bod->positions->count())
                            <div class="og-top-positions">
                                @foreach($bod->positions as $pos)
                                    <div class="og-top-pos">
                                        <i class="fas fa-briefcase"></i>
                                        {{ $pos->title }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                @if($ceo)
                    <div class="og-top-card ceo">
                        <div class="og-top-icon"><i class="fas fa-user-tie"></i></div>
                        <h3>{{ $ceo->name }}</h3>
                        <span class="og-top-badge"><i class="fas fa-hashtag"></i> {{ $ceo->code }}</span>
                        @if($ceo->positions->count())
                            <div class="og-top-positions">
                                @foreach($ceo->positions as $pos)
                                    <div class="og-top-pos">
                                        <i class="fas fa-briefcase"></i>
                                        {{ $pos->title }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- DEPARTMENTS --}}
        <div class="og-section">
            <div class="og-section-title">Departments</div>
            <div class="og-dept-grid">
                @foreach($departments as $dept)
                    @php
                        $color = 'admin';
                        if (str_contains(strtolower($dept->code), 'prog')) $color = 'prog';
                        elseif (str_contains(strtolower($dept->code), 'meal')) $color = 'meal';
                        elseif (str_contains(strtolower($dept->code), 'ict')) $color = 'ict';
                    @endphp

                    <div class="og-dept-card d-{{ $color }}">
                        <div class="og-dept-header">
                            <div class="og-dept-icon">
                                @if($color === 'admin') <i class="fas fa-briefcase"></i>
                                @elseif($color === 'prog') <i class="fas fa-seedling"></i>
                                @elseif($color === 'meal') <i class="fas fa-chart-line"></i>
                                @else <i class="fas fa-laptop-code"></i>
                                @endif
                            </div>
                            <h3 class="og-dept-title">{{ $dept->name }}</h3>
                            <div class="og-dept-meta">
                                {{ $dept->code }}
                                <span class="og-dept-count"><i class="fas fa-users"></i> {{ $dept->positions->count() }}</span>
                            </div>
                        </div>

                        <div class="og-dept-body">
                            <div class="og-dept-body-label">
                                <i class="fas fa-briefcase"></i> Positions
                            </div>
                            <div class="og-pos-list">
                                @foreach($dept->positions as $pos)
                                    @php
                                        $t = strtolower($pos->title);
                                        $cls = (str_contains($t,'director') || str_contains($t,'manager')) ? 'lead' : '';
                                    @endphp
                                    <a href="{{ route('positions.show', $pos) }}" class="og-pos-item {{ $cls }}">
                                        <i class="fas fa-briefcase"></i>
                                        <span class="og-pos-title">{{ $pos->title }}</span>
                                        <span class="og-pos-code">{{ $pos->code }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
