/**
 * SAPTA Global Action System
 * Replaces ALL browser confirms with beautiful Tailwind modals
 */

const SAPTA = {
    config: {
        delete: {
            title: 'Delete Item?',
            message: 'This action cannot be undone.',
            confirmText: 'Yes, Delete',
            color: 'red',
            icon: 'fa-trash',
        },
        approve: {
            title: 'Approve Item?',
            message: 'Are you sure you want to approve?',
            confirmText: 'Yes, Approve',
            color: 'green',
            icon: 'fa-check-circle',
        },
        reject: {
            title: 'Reject Item?',
            message: 'Are you sure you want to reject?',
            confirmText: 'Yes, Reject',
            color: 'red',
            icon: 'fa-times-circle',
        },
        return: {
            title: 'Return for Correction?',
            message: 'The item will be returned to the submitter.',
            confirmText: 'Yes, Return',
            color: 'yellow',
            icon: 'fa-undo',
        },
        complete: {
            title: 'Mark as Complete?',
            message: 'This will mark as completed.',
            confirmText: 'Yes, Complete',
            color: 'green',
            icon: 'fa-check-double',
        },
        check: {
            title: 'Check Voucher?',
            message: 'This voucher will be marked as checked.',
            confirmText: 'Yes, Check',
            color: 'blue',
            icon: 'fa-clipboard-check',
        },
        authorize: {
            title: 'Authorize Voucher?',
            message: 'This voucher will be authorized for payment.',
            confirmText: 'Yes, Authorize',
            color: 'green',
            icon: 'fa-user-check',
        },
        markPaid: {
            title: 'Mark as Paid?',
            message: 'This voucher will be marked as PAID. This action cannot be undone.',
            confirmText: 'Yes, Mark as Paid',
            color: 'purple',
            icon: 'fa-money-bill-wave',
        },
        deactivate: {
            title: 'Deactivate Employee?',
            message: 'The employee will be deactivated and unable to log in.',
            confirmText: 'Yes, Deactivate',
            color: 'yellow',
            icon: 'fa-user-slash',
        },
        terminate: {
            title: 'Terminate Employee?',
            message: 'The employee will be terminated and unable to log in. This action cannot be undone.',
            confirmText: 'Yes, Terminate',
            color: 'red',
            icon: 'fa-user-times',
        },
        activate_employee: {
            title: 'Activate Employee?',
            message: 'The employee will be activated and able to log in again.',
            confirmText: 'Yes, Activate',
            color: 'green',
            icon: 'fa-user-check',
        },
        leave_approve: {
            title: 'Approve Leave Request?',
            message: 'This leave request will be approved.',
            confirmText: 'Yes, Approve',
            color: 'green',
            icon: 'fa-check-circle',
        },
        leave_return: {
            title: 'Return Leave Request?',
            message: 'This leave request will be returned for correction.',
            confirmText: 'Yes, Return',
            color: 'yellow',
            icon: 'fa-undo',
        },
        suspend: {
            title: 'Suspend User?',
            message: 'This user will be suspended and unable to log in.',
            confirmText: 'Yes, Suspend',
            color: 'red',
            icon: 'fa-ban',
        },
        activate: {
            title: 'Activate User?',
            message: 'This user will be activated and able to log in again.',
            confirmText: 'Yes, Activate',
            color: 'green',
            icon: 'fa-check-circle',
        },
    },

    colors: {
        red: { bg: 'bg-red-600 hover:bg-red-700', iconBg: 'bg-red-100 text-red-600' },
        green: { bg: 'bg-emerald-600 hover:bg-emerald-700', iconBg: 'bg-emerald-100 text-emerald-600' },
        blue: { bg: 'bg-blue-600 hover:bg-blue-700', iconBg: 'bg-blue-100 text-blue-600' },
        yellow: { bg: 'bg-amber-600 hover:bg-amber-700', iconBg: 'bg-amber-100 text-amber-600' },
        purple: { bg: 'bg-purple-600 hover:bg-purple-700', iconBg: 'bg-purple-100 text-purple-600' },
    },

    /**
     * Confirm form submission with modal
     */
    confirm(formElement, options = {}) {
        // Prevent form submission immediately
        if (window.event) {
            window.event.preventDefault();
            window.event.returnValue = false;
        }

        const action = options.action || 'delete';
        const config = this.config[action] || this.config.delete;
        const item = options.item || 'this item';
        const customTitle = options.title || config.title;
        const customMessage = options.message || config.message;
        const customConfirmText = options.confirmText || config.confirmText;
        const color = options.color || config.color;
        const icon = options.icon || config.icon;

        this.showModal({
            title: customTitle,
            message: customMessage + (item !== 'this item' ? ' (' + item + ')' : ''),
            confirmText: customConfirmText,
            color: color,
            icon: icon,
            onConfirm: () => {
                formElement.submit();
            },
        });

        return false;
    },

    /**
     * Show the modal
     */
    showModal({ title, message, confirmText, color, icon, onConfirm }) {
        const c = this.colors[color] || this.colors.red;

        // Remove existing modals
        document.querySelectorAll('.sapta-modal-overlay').forEach(m => m.remove());

        // Create modal
        const modal = document.createElement('div');
        modal.className = 'sapta-modal-overlay fixed inset-0 z-50 flex items-center justify-center p-4';
        modal.style.background = 'rgba(0,0,0,0.5)';
        modal.style.backdropFilter = 'blur(4px)';

        modal.innerHTML = `
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6" style="animation: saptaModalIn 0.2s ease-out;">
                <div class="text-center">
                    <div class="w-16 h-16 ${c.iconBg} rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas ${icon} text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-extrabold text-slate-900 mb-2">${title}</h3>
                    <p class="text-sm text-slate-600 mb-6">${message}</p>
                    <div class="flex gap-3">
                        <button type="button" class="sapta-modal-cancel flex-1 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition">
                            Cancel
                        </button>
                        <button type="button" class="sapta-modal-confirm flex-1 px-5 py-2.5 ${c.bg} text-white font-bold rounded-xl transition">
                            ${confirmText}
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modal);
        document.body.style.overflow = 'hidden';

        // Cancel
        modal.querySelector('.sapta-modal-cancel').addEventListener('click', () => this.closeModal(modal));

        // Confirm
        modal.querySelector('.sapta-modal-confirm').addEventListener('click', () => {
            this.closeModal(modal);
            if (typeof onConfirm === 'function') onConfirm();
        });

        // Backdrop close
        modal.addEventListener('click', (e) => {
            if (e.target === modal) this.closeModal(modal);
        });

        // ESC key
        const escHandler = (e) => {
            if (e.key === 'Escape') {
                this.closeModal(modal);
                document.removeEventListener('keydown', escHandler);
            }
        };
        document.addEventListener('keydown', escHandler);

        // Add animation
        if (!document.getElementById('sapta-modal-style')) {
            const style = document.createElement('style');
            style.id = 'sapta-modal-style';
            style.textContent = '@keyframes saptaModalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }';
            document.head.appendChild(style);
        }
    },

    closeModal(modal) {
        if (modal) {
            modal.style.animation = 'saptaModalIn 0.15s ease-in reverse';
            setTimeout(() => {
                modal.remove();
                document.body.style.overflow = '';
            }, 150);
        }
    },
};

// Attach globally
window.SAPTA = SAPTA;
/* ============================================================
   SAPTA ACTION FORM BINDING
   Binds all forms with class "sapta-action-form" to SAPTA.confirm
   Uses the correct signature: SAPTA.confirm(formElement, { action: '...' })
   ============================================================ */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form.sapta-action-form').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var actionKey = form.getAttribute('data-action');

            // Kama action haipo — submit moja kwa moja
            if (!actionKey || !SAPTA.config[actionKey]) {
                form.submit();
                return;
            }

            // Tumia signature sahihi ya SAPTA.confirm
            SAPTA.confirm(form, {
                action: actionKey
            });
        });
    });
});

/* ============================================================
   SUSPEND EMPLOYEE — SweetAlert2 with Reason Input
   Kwa form yenye data-action="suspend_employee"
   ============================================================ */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-action="suspend_employee"]').forEach(function (form) {
        // Ondoa binding ya kawaida
        form.removeEventListener('submit', function () {});
        
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            e.stopPropagation();

            Swal.fire({
                title: 'Suspend Employee?',
                html: '<p style="color:#64748b; margin-bottom:16px;">The employee will be suspended and unable to log in.</p>',
                input: 'textarea',
                inputLabel: 'Reason for suspension',
                inputPlaceholder: 'Andika sababu ya kusimamisha mfanyakazi huyu...',
                inputAttributes: {
                    'required': 'required',
                    'maxlength': 1000,
                    'rows': 4,
                },
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-user-clock"></i> Yes, Suspend',
                confirmButtonColor: '#7c3aed',
                cancelButtonText: 'Cancel',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
                focusConfirm: false,
                inputValidator: (value) => {
                    if (!value || value.trim() === '') {
                        return 'Sababu inahitajika ili kuendelea!';
                    }
                    if (value.trim().length < 5) {
                        return 'Sababu ni fupi sana — andika zaidi ya herufi 5.';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Ongeza hidden input ya suspension_reason
                    var existing = form.querySelector('input[name="suspension_reason"]');
                    if (existing) existing.remove();
                    
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'suspension_reason';
                    input.value = result.value.trim();
                    form.appendChild(input);
                    
                    // Submit form
                    form.submit();
                }
            });
        });
    });
});

/* ============================================================
   SUSPEND EMPLOYEE — SweetAlert2 with Reason Input
   Kwa form yenye data-action="suspend_employee"
   ============================================================ */
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-action="suspend_employee"]').forEach(function (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            e.stopPropagation();

            Swal.fire({
                title: 'Suspend Employee?',
                html: '<p style="color:#64748b; margin-bottom:16px;">The employee will be suspended and unable to log in.</p>',
                input: 'textarea',
                inputLabel: 'Reason for suspension',
                inputPlaceholder: 'Andika sababu ya kusimamisha mfanyakazi huyu...',
                inputAttributes: {
                    'required': 'required',
                    'maxlength': 1000,
                    'rows': 4,
                },
                showCancelButton: true,
                confirmButtonText: 'Yes, Suspend',
                confirmButtonColor: '#7c3aed',
                cancelButtonText: 'Cancel',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
                focusConfirm: false,
                inputValidator: (value) => {
                    if (!value || value.trim() === '') {
                        return 'Sababu inahitajika ili kuendelea!';
                    }
                    if (value.trim().length < 5) {
                        return 'Sababu ni fupi sana — andika zaidi ya herufi 5.';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    var existing = form.querySelector('input[name="suspension_reason"]');
                    if (existing) existing.remove();

                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'suspension_reason';
                    input.value = result.value.trim();
                    form.appendChild(input);

                    form.submit();
                }
            });
        });
    });
});