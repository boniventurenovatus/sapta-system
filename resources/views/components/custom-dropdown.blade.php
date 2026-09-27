@props([
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => '— Select —',
    'required' => false,
    'id' => null,
])

@php
    $id = $id ?? 'dropdown-' . uniqid();
    $selectedOption = collect($options)->firstWhere('value', $selected);
@endphp

<div class="custom-dropdown" data-dropdown="{{ $id }}">
    <input type="hidden"
           name="{{ $name }}"
           id="{{ $id }}"
           value="{{ $selected }}"
           @if($required) required @endif>

    <button type="button"
            class="custom-dropdown-toggle"
            onclick="toggleDropdown('{{ $id }}')">
        <span class="custom-dropdown-label">
            {{ $selectedOption['label'] ?? $placeholder }}
        </span>
        <i class="fas fa-chevron-down custom-dropdown-icon"></i>
    </button>

    <div class="custom-dropdown-menu" id="{{ $id }}-menu">
        <div class="custom-dropdown-search">
            <input type="text"
                   placeholder="Search..."
                   oninput="filterDropdown('{{ $id }}', this.value)">
        </div>
        <div class="custom-dropdown-options">
            @foreach($options as $option)
                <div class="custom-dropdown-option {{ $selected == $option['value'] ? 'selected' : '' }}"
                     data-value="{{ $option['value'] }}"
                     data-label="{{ $option['label'] }}"
                     onclick="selectDropdownOption('{{ $id }}', '{{ $option['value'] }}', '{{ $option['label'] }}')">
                    {{ $option['label'] }}
                </div>
            @endforeach
        </div>
    </div>
</div>

@once
@push('styles')
<style>
.custom-dropdown {
    position: relative;
    width: 100%;
}

.custom-dropdown-toggle {
    width: 100%;
    padding: 10px 14px;
    background: #fff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    font-size: 14px;
    text-align: left;
    transition: all 0.2s;
}

.custom-dropdown-toggle:hover {
    border-color: #94a3b8;
}

.custom-dropdown-toggle:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.custom-dropdown-icon {
    color: #64748b;
    transition: transform 0.2s;
}

.custom-dropdown.open .custom-dropdown-icon {
    transform: rotate(180deg);
}

.custom-dropdown-menu {
    display: none;
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    z-index: 100;
    max-height: 300px;
    overflow: hidden;
    flex-direction: column;
}

.custom-dropdown.open .custom-dropdown-menu {
    display: flex;
}

.custom-dropdown-search {
    padding: 8px;
    border-bottom: 1px solid #e2e8f0;
}

.custom-dropdown-search input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    font-size: 13px;
    outline: none;
}

.custom-dropdown-search input:focus {
    border-color: #2563eb;
}

.custom-dropdown-options {
    overflow-y: auto;
    max-height: 240px;
}

.custom-dropdown-option {
    padding: 10px 14px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.15s;
}

.custom-dropdown-option:hover {
    background: #f1f5f9;
}

.custom-dropdown-option.selected {
    background: #eff6ff;
    color: #2563eb;
    font-weight: 600;
}

/* MOBILE */
@media (max-width: 768px) {
    .custom-dropdown-toggle {
        font-size: 15px;
        padding: 12px 14px;
    }

    .custom-dropdown-menu {
        position: fixed;
        top: auto;
        bottom: 0;
        left: 0;
        right: 0;
        max-height: 60vh;
        border-radius: 16px 16px 0 0;
        box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.15);
    }

    .custom-dropdown-options {
        max-height: 50vh;
    }

    .custom-dropdown-option {
        padding: 14px 16px;
        font-size: 15px;
    }
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    window.toggleDropdown = function(id) {
        const dropdown = document.querySelector(`[data-dropdown="${id}"]`);
        if (!dropdown) return;

        const isOpen = dropdown.classList.contains('open');

        // Funga zote
        document.querySelectorAll('.custom-dropdown.open').forEach(d => {
            d.classList.remove('open');
        });

        if (!isOpen) {
            dropdown.classList.add('open');
            const searchInput = dropdown.querySelector('.custom-dropdown-search input');
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 100);
            }
        }
    };

    window.selectDropdownOption = function(id, value, label) {
        const dropdown = document.querySelector(`[data-dropdown="${id}"]`);
        if (!dropdown) return;

        const hiddenInput = document.getElementById(id);
        const labelEl = dropdown.querySelector('.custom-dropdown-label');

        if (hiddenInput) hiddenInput.value = value;
        if (labelEl) labelEl.textContent = label;

        // Update selected class
        dropdown.querySelectorAll('.custom-dropdown-option').forEach(opt => {
            opt.classList.toggle('selected', opt.dataset.value == value);
        });

        dropdown.classList.remove('open');

        // Trigger change event
        if (hiddenInput) {
            hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
        }
    };

    window.filterDropdown = function(id, query) {
        const dropdown = document.querySelector(`[data-dropdown="${id}"]`);
        if (!dropdown) return;

        const options = dropdown.querySelectorAll('.custom-dropdown-option');
        const q = query.toLowerCase();

        options.forEach(opt => {
            const label = opt.dataset.label.toLowerCase();
            opt.style.display = label.includes(q) ? 'block' : 'none';
        });
    };

    // Funga dropdown ukibofya nje
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dropdown')) {
            document.querySelectorAll('.custom-dropdown.open').forEach(d => {
                d.classList.remove('open');
            });
        }
    });

    // Funga kwa ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.custom-dropdown.open').forEach(d => {
                d.classList.remove('open');
            });
        }
    });
})();
</script>
@endpush
@endonce