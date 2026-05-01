/**
 * Shared action modal logic (confirmation + rejection with reason)
 * Used on: Approvals, Pets (deceased reports)
 *
 * Usage: Add these attributes to any button/form:
 *   For confirm: <button data-action-confirm data-url="{{ route('...') }}" data-title="..." data-message="...">
 *   For reject:  <button data-action-reject data-url="{{ route('...') }}" data-title="..." data-message="...">
 */

document.addEventListener('DOMContentLoaded', () => {
    const confirmModal = document.getElementById('action-confirm-modal');
    const rejectModal  = document.getElementById('action-reject-modal');
    const confirmForm  = document.getElementById('confirm-action-form');
    const rejectForm   = document.getElementById('reject-action-form');
    const rejectTextarea = document.getElementById('rejection-reason');

    if (!confirmModal || !rejectModal) return;

    document.addEventListener('click', (e) => {
        // ─── Open confirm modal ───
        const confirmBtn = e.target.closest('[data-action-confirm]');
        if (confirmBtn) {
            e.preventDefault();
            const url = confirmBtn.dataset.url;
            const title = confirmBtn.dataset.title || 'Confirm Action';
            const message = confirmBtn.dataset.message || 'Are you sure you want to proceed?';

            document.getElementById('confirm-modal-title').textContent = title;
            document.getElementById('confirm-modal-message').textContent = message;
            confirmForm.action = url;

            confirmModal.classList.add('active');
            return;
        }

        // ─── Open reject modal ───
        const rejectBtn = e.target.closest('[data-action-reject]');
        if (rejectBtn) {
            e.preventDefault();
            const url = rejectBtn.dataset.url;
            const title = rejectBtn.dataset.title || 'Reject';
            const message = rejectBtn.dataset.message || 'Please provide a reason for rejection.';

            document.getElementById('reject-modal-title').textContent = title;
            document.getElementById('reject-modal-message').textContent = message;
            rejectForm.action = url;
            rejectTextarea.value = '';

            rejectModal.classList.add('active');
            setTimeout(() => rejectTextarea.focus(), 100);
            return;
        }

        // ─── Close handlers ───
        if (e.target.closest('[data-close-confirm]')) {
            confirmModal.classList.remove('active');
        }
        if (e.target.closest('[data-close-reject]')) {
            rejectModal.classList.remove('active');
        }

        // Click overlay to close
        if (e.target === confirmModal) {
            confirmModal.classList.remove('active');
        }
        if (e.target === rejectModal) {
            rejectModal.classList.remove('active');
        }
    });
});