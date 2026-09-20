/**
 * Modal Confirmation Helper
 * Replaces browser confirm() with beautiful Tailwind modals
 */

function showModal(modalId, options) {
    const modal = document.getElementById(modalId);
    const confirmBtn = document.getElementById(modalId + '-confirm');
    
    if (!modal || !confirmBtn) {
        console.warn('Modal not found:', modalId);
        return;
    }

    // Update title & message if provided
    if (options && options.title) {
        modal.querySelector('h3').textContent = options.title;
    }
    if (options && options.message) {
        modal.querySelector('p').textContent = options.message;
    }

    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Handle confirm
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);
    
    newConfirmBtn.addEventListener('click', function() {
        closeModal(modalId);
        if (options && options.onConfirm) {
            options.onConfirm();
        }
    });
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
}

/**
 * Confirm form submission with modal
 */
function confirmForm(formElement, options) {
    event.preventDefault();
    
    const modalId = 'confirm-modal-' + Date.now();
    
    // Create modal dynamically
    const modal = document.createElement('div');
    modal.id = modalId;
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4';
    modal.style.background = 'rgba(0,0,0,0.5)';
    modal.style.backdropFilter = 'blur(4px)';
    
    const color = options.color || 'red';
    const colorClasses = {
        'red': 'bg-red-600 hover:bg-red-700',
        'green': 'bg-emerald-600 hover:bg-emerald-700',
        'blue': 'bg-blue-600 hover:bg-blue-700',
        'yellow': 'bg-amber-600 hover:bg-amber-700',
    };
    const iconClasses = {
        'red': 'bg-red-100 text-red-600',
        'green': 'bg-emerald-100 text-emerald-600',
        'blue': 'bg-blue-100 text-blue-600',
        'yellow': 'bg-amber-100 text-amber-600',
    };
    const icons = {
        'red': 'fa-exclamation-triangle',
        'green': 'fa-check-circle',
        'blue': 'fa-info-circle',
        'yellow': 'fa-exclamation-circle',
    };

    modal.innerHTML = `
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6" style="animation: modalIn 0.2s ease-out;">
            <div class="text-center">
                <div class="w-16 h-16 ${iconClasses[color]} rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas ${icons[color]} text-2xl"></i>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900 mb-2">${options.title || 'Confirm Action'}</h3>
                <p class="text-sm text-slate-600 mb-6">${options.message || 'Are you sure?'}</p>
                <div class="flex gap-3">
                    <button type="button" class="modal-cancel flex-1 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
                        ${options.cancelText || 'Cancel'}
                    </button>
                    <button type="button" class="modal-confirm flex-1 px-5 py-2.5 ${colorClasses[color]} text-white font-bold rounded-xl transition">
                        ${options.confirmText || 'Confirm'}
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    document.body.style.overflow = 'hidden';
    
    // Cancel
    modal.querySelector('.modal-cancel').addEventListener('click', function() {
        modal.remove();
        document.body.style.overflow = '';
    });
    
    // Confirm
    modal.querySelector('.modal-confirm').addEventListener('click', function() {
        modal.remove();
        document.body.style.overflow = '';
        formElement.submit();
    });
    
    // Close on backdrop
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
            document.body.style.overflow = '';
        }
    });
    
    // Add keyframe animation
    if (!document.getElementById('modal-anim-style')) {
        const style = document.createElement('style');
        style.id = 'modal-anim-style';
        style.textContent = '@keyframes modalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }';
        document.head.appendChild(style);
    }
}