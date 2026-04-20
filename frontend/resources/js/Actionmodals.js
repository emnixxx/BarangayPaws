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

    // ─── Open confirm modal ───
    document.querySelectorAll('[data-action-confirm]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const url = btn.dataset.url;
            const title = btn.dataset.title || 'Confirm Action';
            const message = btn.dataset.message || 'Are you sure you want to proceed?';

            document.getElementById('confirm-modal-title').textContent = title;
            document.getElementById('confirm-modal-message').textContent = message;
            confirmForm.action = url;

            confirmModal.classList.add('active');
        });
    });

    // ─── Open reject modal ───
    document.querySelectorAll('[data-action-reject]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const url = btn.dataset.url;
            const title = btn.dataset.title || 'Reject';
            const message = btn.dataset.message || 'Please provide a reason for rejection.';

            document.getElementById('reject-modal-title').textContent = title;
            document.getElementById('reject-modal-message').textContent = message;
            rejectForm.action = url;
            rejectTextarea.value = '';

            rejectModal.classList.add('active');
            setTimeout(() => rejectTextarea.focus(), 100);
        });
    });

    // ─── Close handlers ───
    document.querySelectorAll('[data-close-confirm]').forEach(btn => {
        btn.addEventListener('click', () => confirmModal.classList.remove('active'));
    });

    document.querySelectorAll('[data-close-reject]').forEach(btn => {
        btn.addEventListener('click', () => rejectModal.classList.remove('active'));
    });

    // Click overlay to close
    confirmModal.addEventListener('click', (e) => {
        if (e.target === confirmModal) confirmModal.classList.remove('active');
    });

    rejectModal.addEventListener('click', (e) => {
        if (e.target === rejectModal) rejectModal.classList.remove('active');
    });
});