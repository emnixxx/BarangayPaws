/**
 * Generic Action Modals JS
 * Handles generic confirmation and rejection modals via data attributes.
 */
document.addEventListener('DOMContentLoaded', () => {
    const confirmModal = document.getElementById('generic-confirm-modal');
    const rejectModal = document.getElementById('generic-reject-modal');

    // Confirm buttons
    document.querySelectorAll('[data-action-confirm]').forEach(btn => {
        btn.addEventListener('click', () => {
            const url = btn.dataset.url;
            const title = btn.dataset.title || 'Confirm Action';
            const message = btn.dataset.message || 'Are you sure you want to proceed?';

            document.getElementById('confirm-modal-title').textContent = title;
            document.getElementById('confirm-modal-message').textContent = message;
            document.getElementById('generic-confirm-form').action = url;

            confirmModal.classList.add('active');
        });
    });

    // Reject buttons
    document.querySelectorAll('[data-action-reject]').forEach(btn => {
        btn.addEventListener('click', () => {
            const url = btn.dataset.url;
            const title = btn.dataset.title || 'Reject Action';
            const message = btn.dataset.message || 'Are you sure you want to reject this?';

            document.getElementById('reject-modal-title').textContent = title;
            document.getElementById('reject-modal-message').textContent = message;
            document.getElementById('generic-reject-form').action = url;
            document.getElementById('generic_rejection_reason').value = '';

            rejectModal.classList.add('active');
        });
    });

    // Close buttons
    document.querySelectorAll('[data-close-action]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal-overlay');
            if (modal) modal.classList.remove('active');
        });
    });

    // Click outside overlay to close
    [confirmModal, rejectModal].forEach(modal => {
        if (!modal) return;
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('active');
        });
    });
});
