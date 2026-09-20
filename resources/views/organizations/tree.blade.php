@extends('layouts.sapta')

@section('title', 'Organization Tree')

@section('content')
<style>
    .org-tree-container {
        padding: 20px 0;
    }
    
    .org-tree-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .org-tree-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a2e;
        margin: 0;
    }
    
    .org-tree-header p {
        color: #8898aa;
        margin: 4px 0 0;
        font-size: 14px;
    }
    
    .org-tree {
        background: white;
        border-radius: 16px;
        border: 1px solid #e8ecf1;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    /* Tree styles */
    .tree {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .tree li {
        list-style: none;
        padding: 10px 0 0 20px;
        position: relative;
    }
    
    .tree li::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 2px;
        background: #e8ecf1;
    }
    
    .tree li::after {
        content: '';
        position: absolute;
        top: 30px;
        left: 0;
        width: 20px;
        height: 2px;
        background: #e8ecf1;
    }
    
    .tree li:last-child::before {
        height: 30px;
    }
    
    .tree li:last-child::after {
        height: 2px;
    }
    
    .tree .node {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        background: #f8f9fa;
        border-radius: 10px;
        border: 1px solid #e8ecf1;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        margin-bottom: 4px;
    }
    
    .tree .node:hover {
        background: #f0f2f5;
        transform: translateX(4px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }
    
    .tree .node .node-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
        background: #dbeafe;
        color: #1a5276;
    }
    
    .tree .node .node-info {
        flex: 1;
    }
    
    .tree .node .node-info .node-name {
        font-size: 14px;
        font-weight: 600;
        color: #1a1a2e;
    }
    
    .tree .node .node-info .node-detail {
        font-size: 12px;
        color: #8898aa;
    }
    
    .tree .node .node-status {
        font-size: 11px;
        padding: 2px 12px;
        border-radius: 12px;
        font-weight: 600;
    }
    
    .tree .node .node-status.active {
        background: #d4edda;
        color: #155724;
    }
    
    .tree .node .node-status.inactive {
        background: #f8d7da;
        color: #721c24;
    }
    
    .tree .node .node-actions {
        display: flex;
        gap: 6px;
    }
    
    .tree .node .node-actions a {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .tree .node .node-actions .btn-view {
        background: #e8ecf1;
        color: #4a5a6f;
    }
    
    .tree .node .node-actions .btn-view:hover {
        background: #d5d9e0;
    }
    
    .tree .node .node-actions .btn-edit {
        background: #fff3cd;
        color: #856404;
    }
    
    .tree .node .node-actions .btn-edit:hover {
        background: #ffeaa7;
    }
    
    .tree .node .toggle-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 14px;
        color: #8898aa;
        transition: transform 0.3s ease;
        padding: 4px 8px;
    }
    
    .tree .node .toggle-btn.rotated {
        transform: rotate(90deg);
    }
    
    .tree .children {
        padding-left: 20px;
        display: none;
    }
    
    .tree .children.open {
        display: block;
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
        .tree .node {
            flex-wrap: wrap;
        }
        .tree .node .node-actions {
            width: 100%;
            margin-top: 8px;
        }
    }
</style>

<div class="org-tree-container">
    <div class="org-tree-header">
        <div>
            <h1 data-en="Organization Tree" data-sw="Mti wa Mashirika">Organization Tree</h1>
            <p data-en="View organizational hierarchy" data-sw="Tazama muundo wa mashirika">View organizational hierarchy</p>
        </div>
        <a href="{{ route('organizations.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> <span data-en="Add Organization" data-sw="Ongeza Shirika">Add Organization</span>
        </a>
    </div>

    <div class="org-tree">
        @if(isset($organizations) && count($organizations) > 0)
            <ul class="tree">
                @foreach($organizations as $org)
                    @include('organizations.partials.tree-node', ['organization' => $org])
                @endforeach
            </ul>
        @else
            <div class="empty-state">
                <i class="fas fa-sitemap"></i>
                <p data-en="No organizations found" data-sw="Hakuna mashirika">No organizations found</p>
                <p style="font-size:13px;" data-en="Add your first organization to get started" data-sw="Ongeza shirika lako la kwanza kuanza">Add your first organization to get started</p>
            </div>
        @endif
    </div>
</div>

<script>
    function toggleChildren(element) {
        const children = element.nextElementSibling;
        const icon = element.querySelector('.toggle-btn');
        if (children) {
            children.classList.toggle('open');
            if (icon) {
                icon.classList.toggle('rotated');
            }
        }
    }
    
    // Auto-expand all nodes on load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.children').forEach(function(el) {
            el.classList.add('open');
        });
    });
</script>
@endsection
