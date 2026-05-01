/**
 * BarangayPaws – Residents Page JS
 * Handles search filter and view details modal (with pets).
 */

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('residents-search');
    const tableBody   = document.getElementById('residents-table-body');
    const viewModal   = document.getElementById('resident-view-modal');

    if (!tableBody) return;

    // ─── Live search / filter ───
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase().trim();
            const rows  = tableBody.querySelectorAll('tr[data-id]');

            rows.forEach(row => {
                if (!query) { row.style.display = ''; return; }

                const name    = (row.dataset.name    || '').toLowerCase();
                const email   = (row.dataset.email   || '').toLowerCase();
                const phone   = (row.dataset.contact || '').toLowerCase();
                const address = (row.dataset.address || '').toLowerCase();
                const gender  = (row.dataset.gender  || '').toLowerCase();
                const joined  = (row.dataset.joined  || '').toLowerCase();
                const visible = (row.textContent     || '').toLowerCase();

                // Also search the resident's pets (stored as JSON in data-pets)
                let petsHay = '';
                try {
                    const pets = JSON.parse(row.dataset.pets || '[]');
                    petsHay = pets.map(p => `${p.pet_name||''} ${p.pet_type||''} ${p.breed||''} ${p.status||''}`).join(' ').toLowerCase();
                } catch (_) {}

                const matches = name.includes(query)
                             || email.includes(query)
                             || phone.includes(query)
                             || address.includes(query)
                             || gender.includes(query)
                             || joined.includes(query)
                             || petsHay.includes(query)
                             || visible.includes(query);

                row.style.display = matches ? '' : 'none';
            });
        });
    }

    // ─── View button → open modal ───
    tableBody.addEventListener('click', (e) => {
        const viewBtn = e.target.closest('.view-btn');
        if (!viewBtn) return;

        const row = viewBtn.closest('tr');
        if (!row) return;

        const name = row.dataset.name || '';
        const pets = JSON.parse(row.dataset.pets || '[]');

        // Fill modal fields
        document.getElementById('modal-avatar').textContent = name.substring(0, 2).toUpperCase();
        document.getElementById('modal-name').textContent = name;
        document.getElementById('modal-email').textContent = row.dataset.email || '—';
        document.getElementById('modal-gender').textContent = row.dataset.gender || '—';
        document.getElementById('modal-contact').textContent = row.dataset.contact || '—';
        document.getElementById('modal-joined').textContent = row.dataset.joined || '—';
        document.getElementById('modal-address').textContent = row.dataset.address || '—';

        // Fill pets list
        const petsList = document.getElementById('modal-pets-list');
        document.getElementById('modal-pets-count').textContent = pets.length;

        if (pets.length === 0) {
            petsList.innerHTML = `<div style="text-align:center; padding:16px; color:#9ca3af; font-size:13px;">No pets registered</div>`;
        } else {
            petsList.innerHTML = pets.map(pet => {
                const statusColors = {
                    approved: { bg: '#d1fae5', text: '#065f46' },
                    pending:  { bg: '#ffedd5', text: '#9a3412' },
                    rejected: { bg: '#fee2e2', text: '#991b1b' },
                    deceased: { bg: '#f3e8ff', text: '#6b21a8' },
                };
                const sc = statusColors[pet.status] || statusColors.pending;

                return `
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 12px; background:#f9fafb; border-radius:8px;">
                        <div>
                            <div style="font-size:14px; font-weight:600; color:#111827;">${pet.pet_name}</div>
                            <div style="font-size:12px; color:#6b7280;">
                                ${pet.pet_type.charAt(0).toUpperCase() + pet.pet_type.slice(1)}${pet.breed ? ' · ' + pet.breed : ''}
                            </div>
                        </div>
                        <span style="background:${sc.bg}; color:${sc.text}; padding:3px 10px; border-radius:12px; font-size:11px; font-weight:600; text-transform:capitalize;">
                            ${pet.status}
                        </span>
                    </div>
                `;
            }).join('');
        }

        viewModal.classList.add('active');
    });

    // ─── Close modal ───
    document.querySelectorAll('[data-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            viewModal.classList.remove('active');
        });
    });

    // Click overlay to close
    if (viewModal) {
        viewModal.addEventListener('click', (e) => {
            if (e.target === viewModal) {
                viewModal.classList.remove('active');
            }
        });
    }

    // ─── Delete confirmation modal ───
    const deleteModal   = document.getElementById('resident-delete-modal');
    const deleteForm    = document.getElementById('delete-resident-form');
    const deleteNameEl  = document.getElementById('delete-resident-name');

    if (deleteModal && deleteForm) {
        const reasonInput = document.getElementById('delete-rejection-reason');
        const reasonError = document.getElementById('delete-reason-error');

        // Open modal when delete button clicked
        tableBody.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-delete-btn]');
            if (!btn) return;

            deleteNameEl.textContent = btn.dataset.name || 'this resident';
            deleteForm.action        = btn.dataset.action;
            if (reasonInput) reasonInput.value = '';
            if (reasonError) reasonError.style.display = 'none';
            deleteModal.classList.add('active');
            setTimeout(() => reasonInput && reasonInput.focus(), 50);
        });

        // Validate on submit
        deleteForm.addEventListener('submit', (e) => {
            if (!reasonInput || !reasonInput.value.trim()) {
                e.preventDefault();
                if (reasonError) reasonError.style.display = 'block';
                if (reasonInput) reasonInput.focus();
            }
        });

        // Close handlers
        deleteModal.querySelectorAll('[data-close-delete]').forEach(el => {
            el.addEventListener('click', () => deleteModal.classList.remove('active'));
        });

        // Click overlay to close
        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) deleteModal.classList.remove('active');
        });

        // ESC to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') deleteModal.classList.remove('active');
        });
    }
});