/**
 * BarangayPaws – Approvals Page JS
 * Handles tab switching and approve/reject modals for residents and pets.
 */

document.addEventListener('DOMContentLoaded', () => {
    const tabBtns      = document.querySelectorAll('.tab-btn');
    const tabContents  = document.querySelectorAll('.tab-content');
    const approveModal = document.getElementById('approve-confirm-modal');
    const rejectModal  = document.getElementById('reject-confirm-modal');
    const approveForm  = document.getElementById('approve-form');
    const rejectForm   = document.getElementById('reject-form');
    const approveName  = document.getElementById('approve-name');
    const rejectName   = document.getElementById('reject-name');
    const rejectReason = document.getElementById('rejection_reason');

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

    // ─── Approve buttons ───
    document.querySelectorAll('.approve-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const type = e.currentTarget.dataset.type; // 'resident' or 'pet'
            const id   = e.currentTarget.dataset.id;
            const name = e.currentTarget.dataset.name;

            approveForm.action = `/approvals/${type}/${id}/approve`;
            approveName.textContent = name;
            approveModal.classList.add('active');
        });
    });

    // ─── Reject buttons ───
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const type = e.currentTarget.dataset.type;
            const id   = e.currentTarget.dataset.id;
            const name = e.currentTarget.dataset.name;

            rejectForm.action = `/approvals/${type}/${id}/reject`;
            rejectName.textContent = name;
            rejectReason.value = '';
            rejectModal.classList.add('active');
        });
    });

    // ─── View details buttons ───
    const residentModal = document.getElementById('resident-view-modal');
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const row = e.currentTarget.closest('tr');
            if(!row) return;

            const d = row.dataset;
            document.getElementById('modal-name').textContent = d.name || 'N/A';
            document.getElementById('modal-email').textContent = d.email || 'N/A';
            document.getElementById('modal-gender').textContent = d.gender ? (d.gender.charAt(0).toUpperCase() + d.gender.slice(1)) : 'N/A';
            document.getElementById('modal-contact').textContent = d.contact || 'N/A';
            document.getElementById('modal-joined').textContent = d.joined || 'N/A';
            document.getElementById('modal-address').textContent = d.address || 'N/A';
            
            const initials = d.name ? d.name.substring(0, 2).toUpperCase() : 'AD';
            document.getElementById('modal-avatar').textContent = initials;

            let pets = [];
            try {
                pets = JSON.parse(d.pets);
            } catch(e) {}

            document.getElementById('modal-pets-count').textContent = pets.length;
            const petsList = document.getElementById('modal-pets-list');
            if (pets.length === 0) {
                petsList.innerHTML = `<div style="font-size:13px; color:#9ca3af; font-style:italic;">No pets registered</div>`;
            } else {
                petsList.innerHTML = pets.map(pet => `
                    <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:6px; padding:10px 14px; display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <div style="font-weight:600; font-size:13px; color:#111827;">${pet.pet_name}</div>
                            <div style="font-size:12px; color:#6b7280;">${pet.breed || ''} (${pet.pet_type || ''})</div>
                        </div>
                        <span class="status-badge ${pet.status === 'approved' ? 'approved' : (pet.status === 'pending' ? 'pending' : 'rejected')}">${pet.status ? (pet.status.charAt(0).toUpperCase() + pet.status.slice(1)) : ''}</span>
                    </div>
                `).join('');
            }
            
            residentModal.classList.add('active');
        });
    });

    // ─── Close modals ───
    document.querySelectorAll('[data-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal-overlay');
            if (modal) modal.classList.remove('active');
        });
    });

    // ─── Click overlay to close ───
    [approveModal, rejectModal, residentModal].forEach(modal => {
        if (!modal) return;
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.classList.remove('active');
        });
    });
});