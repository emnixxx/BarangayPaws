/**
 * BarangayPaws – Approvals Page JS
 * Handles tab switching, view modal, and action buttons.
 */

document.addEventListener('DOMContentLoaded', () => {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    // ─── Tab Switching ───
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.dataset.target;

            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            tabContents.forEach(content => {
                if (content.id === targetId) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        });
    });

    // ─── Resident View Modal ───
    const modal = document.getElementById('residentViewModal');
    const closeBtn = document.getElementById('closeResidentModal');
    const viewBtns = document.querySelectorAll('.btn-view');

    viewBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const d = btn.dataset;

            document.getElementById('modalAvatar').textContent = (d.name || '').substring(0, 2).toUpperCase();
            document.getElementById('modalName').textContent = d.name || '—';
            document.getElementById('modalEmail').textContent = d.email || '—';
            document.getElementById('modalContact').textContent = d.contact || '—';
            document.getElementById('modalGender').textContent = d.gender ? d.gender.charAt(0).toUpperCase() + d.gender.slice(1) : '—';
            document.getElementById('modalAddress').textContent = d.address || '—';
            document.getElementById('modalRegistered').textContent = d.registered || '—';

            modal.classList.add('active');
        });
    });

    if (closeBtn) {
        const closeModal = () => modal.classList.remove('active');

        closeBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) closeModal();
        });
    }
});