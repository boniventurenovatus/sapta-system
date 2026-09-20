<li>
    <div class="node">
        @if($organization->children->count() > 0)
            <button class="toggle-btn" onclick="toggleChildren(this)">?</button>
        @else
            <span style="width:24px;"></span>
        @endif
        
        <div class="node-icon">
            <i class="fas fa-building"></i>
        </div>
        
        <div class="node-info">
            <div class="node-name">{{ $organization->name }}</div>
            <div class="node-detail">
                @if($organization->code)
                    {{ $organization->code }} • 
                @endif
                {{ $organization->type ?? 'Department' }}
                @if($organization->parent_id)
                    • Parent: {{ $organization->parent->name ?? 'N/A' }}
                @endif
            </div>
        </div>
        
        <span class="node-status {{ $organization->status ?? 'active' }}">
            <span data-en="{{ ucfirst($organization->status ?? 'active') }}" data-sw="{{ $organization->status == 'active' ? 'Inayotumika' : 'Haijatumika' }}">
                {{ ucfirst($organization->status ?? 'active') }}
            </span>
        </span>
        
        <div class="node-actions">
            <a href="{{ route('organizations.show', $organization->id) }}" class="btn-view" data-en="View" data-sw="Tazama">View</a>
            <a href="{{ route('organizations.edit', $organization->id) }}" class="btn-edit" data-en="Edit" data-sw="Hariri">Edit</a>
        </div>
    </div>
    
    @if($organization->children->count() > 0)
        <ul class="children open">
            @foreach($organization->children as $child)
                @include('organizations.partials.tree-node', ['organization' => $child])
            @endforeach
        </ul>
    @endif
</li>
