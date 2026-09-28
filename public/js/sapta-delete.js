document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form.sapta-delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Proceed',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});

// ============================================================
// SAPTA CONFIRM FORM — SweetAlert2 kwa forms za approve/paid
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form.sapta-confirm-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var message = form.getAttribute('data-confirm') || 'Are you sure?';
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Proceed',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});