document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('pets-search');
    const petsTable   = document.getElementById('all-pets-table');
    const viewModal   = document.getElementById('pet-view-modal');
    const filterBtns  = document.querySelectorAll('.summary-btn[data-filter]');

    if (!petsTable) return;

    let currentFilter = 'all';

    function applyFilters() {
        const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const rows = petsTable.querySelectorAll('tbody tr[data-pet-id]');

        rows.forEach(row => {
            const type   = (row.dataset.type   || '').toLowerCase();
            const status = (row.dataset.status || '').toLowerCase();
            const name   = (row.dataset.name   || '').toLowerCase();
            const owner  = (row.dataset.owner  || '').toLowerCase();
            const breed  = (row.dataset.breed  || '').toLowerCase();

            let filterMatch = true;
            if (currentFilter === 'cat') filterMatch = (type === 'cat');
            else if (currentFilter === 'dog') filterMatch = (type === 'dog');
            else if (currentFilter === 'deceased') filterMatch = (status === 'deceased');

            // Search across name, owner, breed, type, status — and any visible cell text
            let searchMatch = true;
            if (query) {
                const visibleText = (row.textContent || '').toLowerCase();
                searchMatch = name.includes(query)
                           || owner.includes(query)
                           || breed.includes(query)
                           || type.includes(query)
                           || status.includes(query)
                           || visibleText.includes(query);
            }

            row.style.display = (filterMatch && searchMatch) ? '' : 'none';
        });
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentFilter = btn.dataset.filter;
            applyFilters();
        });
    });

    if (searchInput) searchInput.addEventListener('input', applyFilters);

    let currentActiveRow = null;

    // Row click for view modal & Edit button click
    petsTable.addEventListener('click', (e) => {
        const actionBtn = e.target.closest('.action-buttons');
        const deleteBtn = e.target.closest('[data-delete-pet-btn]');
        
        // Handle Edit Button Click in Table Row
        const editBtn = e.target.closest('.action-icon-btn.edit');
        if (editBtn) {
            e.stopPropagation();
            const row = editBtn.closest('tr');
            if (row) openEditHealthModal(row);
            return;
        }

        // If clicking action buttons, ignore row click
        if (actionBtn || deleteBtn) return;

        // View details (Clicking the row)
        const row = e.target.closest('tr[data-pet-id]');
        if (!row) return;

        currentActiveRow = row;

        const d = row.dataset;

        document.getElementById('modal-pet-name').textContent = d.petName || '—';
        document.getElementById('modal-pet-type').textContent = d.petType ? (d.petType.charAt(0).toUpperCase() + d.petType.slice(1)) : '—';

        // ─── Modal pet avatar (icon) ───
        const avatarEl = document.getElementById('modal-pet-avatar');
        if (avatarEl) {
            const t = (d.petType || '').toLowerCase();
            const dogSvg = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10 5l-2-3-3 3v3l-2 1v3l3 1v8h12v-8l3-1v-3l-2-1V5l-3-3-2 3"/><circle cx="9.5" cy="13" r="0.7" fill="currentColor"/><circle cx="14.5" cy="13" r="0.7" fill="currentColor"/><path d="M11 16h2"/></svg>`;
            const catSvg = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4l3 5h10l3-5v9a8 8 0 0 1-16 0V4z"/><circle cx="9" cy="12" r="0.8" fill="currentColor"/><circle cx="15" cy="12" r="0.8" fill="currentColor"/><path d="M12 14l-1 1.5h2L12 14z" fill="currentColor"/><path d="M9 17c1 .8 5 .8 6 0"/></svg>`;
            const pawSvg = `<svg viewBox="0 0 24 24" fill="currentColor"><ellipse cx="7" cy="9" rx="2" ry="2.5"/><ellipse cx="12" cy="6" rx="2" ry="2.5"/><ellipse cx="17" cy="9" rx="2" ry="2.5"/><ellipse cx="12" cy="15" rx="4" ry="3.5"/></svg>`;

            avatarEl.className = 'pet-avatar pet-avatar-lg ' +
                (t === 'dog' ? 'pet-avatar-dog' : t === 'cat' ? 'pet-avatar-cat' : 'pet-avatar-other');
            avatarEl.innerHTML = t === 'dog' ? dogSvg : t === 'cat' ? catSvg : pawSvg;
        }
        document.getElementById('modal-pet-breed').textContent = d.breed || '—';
        document.getElementById('modal-pet-gender').textContent = d.gender || '—';
        document.getElementById('modal-pet-age').textContent = d.age ? (d.age + ' years') : '—';
        document.getElementById('modal-pet-color').textContent = d.color || '—';
        document.getElementById('modal-pet-registered').textContent = d.registered || '—';
        document.getElementById('modal-owner-name').textContent = d.ownerName || '—';
        document.getElementById('modal-owner-contact').textContent = d.ownerContact || '—';

        const statusEl = document.getElementById('modal-pet-status');
        statusEl.textContent = d.status.charAt(0).toUpperCase() + d.status.slice(1);
        statusEl.className = 'status-badge ' + d.status;

        const healthBadge = (label, filled, dateStr) => ({
            text: label + ': ' + (filled ? 'Yes' : 'No'),
            className: 'status-badge ' + (filled ? 'yes' : 'no'),
            date: filled && dateStr ? dateStr : ''
        });

        const vac = healthBadge('Vaccinated', d.vaccinated === '1', d.vaccinatedDate);
        const dew = healthBadge('Dewormed', d.dewormed === '1', d.dewormedDate);
        const spa = healthBadge('Spayed/Neutered', d.spayed === '1', d.spayedDate);

        const vacEl = document.getElementById('modal-vaccinated');
        const vacDateEl = document.getElementById('modal-vaccinated-date');
        const dewEl = document.getElementById('modal-dewormed');
        const dewDateEl = document.getElementById('modal-dewormed-date');
        const spaEl = document.getElementById('modal-spayed');
        const spaDateEl = document.getElementById('modal-spayed-date');

        vacEl.textContent = vac.text; vacEl.className = vac.className;
        vacDateEl.textContent = vac.date ? `Date: ${vac.date}` : '';
        
        dewEl.textContent = dew.text; dewEl.className = dew.className;
        dewDateEl.textContent = dew.date ? `Date: ${dew.date}` : '';
        
        spaEl.textContent = spa.text; spaEl.className = spa.className;
        spaDateEl.textContent = spa.date ? `Date: ${spa.date}` : '';

        const notesWrap = document.getElementById('modal-health-notes-wrap');
        const notesText = document.getElementById('modal-health-notes');
        if (d.healthNotes && d.healthNotes.trim() !== '') {
            notesText.textContent = d.healthNotes;
            notesWrap.style.display = 'block';
        } else {
            notesWrap.style.display = 'none';
        }

        viewModal.classList.add('active');
    });

    // ─── Edit Health Modal Logic ───
    const editHealthModal = document.getElementById('pet-edit-health-modal');
    const editHealthForm  = document.getElementById('edit-health-form');

    function openEditHealthModal(row) {
        if (!row || !editHealthModal) return;
        
        const d = row.dataset;
        
        // Populate edit form
        const isVac = d.vaccinated === '1';
        const isDew = d.dewormed === '1';
        const isSpa = d.spayed === '1';
        
        document.getElementById('input-vaccinated').checked = isVac;
        document.getElementById('input-vaccinated-date').disabled = !isVac;
        document.getElementById('input-vaccinated-date').value = d.vaccinatedDate || '';
        
        document.getElementById('input-dewormed').checked = isDew;
        document.getElementById('input-dewormed-date').disabled = !isDew;
        document.getElementById('input-dewormed-date').value = d.dewormedDate || '';
        
        document.getElementById('input-spayed').checked = isSpa;
        document.getElementById('input-spayed-date').disabled = !isSpa;
        document.getElementById('input-spayed-date').value = d.spayedDate || '';
        
        document.getElementById('input-health-notes').value = d.healthNotes || '';
        
        // Set form action (Assuming route is pets/{id}/health)
        editHealthForm.action = `/pets/${d.petId}/health`;
        
        editHealthModal.classList.add('active');
    }

    // Edit button inside View Modal
    const btnEditHealth = document.getElementById('btn-edit-health-record');
    if (btnEditHealth) {
        btnEditHealth.addEventListener('click', () => {
            viewModal.classList.remove('active');
            if (currentActiveRow) openEditHealthModal(currentActiveRow);
        });
    }

    // Close edit modal
    document.querySelectorAll('[data-close-edit-health]').forEach(btn => {
        btn.addEventListener('click', () => editHealthModal.classList.remove('active'));
    });

    if (editHealthModal) {
        editHealthModal.addEventListener('click', (e) => {
            if (e.target === editHealthModal) editHealthModal.classList.remove('active');
        });
    }

    // Close view modal
    document.querySelectorAll('[data-close-view]').forEach(btn => {
        btn.addEventListener('click', () => viewModal.classList.remove('active'));
    });

    if (viewModal) {
        viewModal.addEventListener('click', (e) => {
            if (e.target === viewModal) viewModal.classList.remove('active');
        });
    }

    // ─── Delete Pet confirmation modal ───
    const deleteModal   = document.getElementById('pet-delete-modal');
    const deleteForm    = document.getElementById('delete-pet-form');
    const deleteNameEl  = document.getElementById('delete-pet-name');
    const reasonInput   = document.getElementById('delete-pet-reason');
    const reasonError   = document.getElementById('delete-pet-reason-error');

    if (deleteModal && deleteForm) {
        petsTable.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-delete-pet-btn]');
            if (!btn) return;

            deleteNameEl.textContent = btn.dataset.name || 'this pet';
            deleteForm.action        = btn.dataset.action;
            if (reasonInput) reasonInput.value = '';
            if (reasonError) reasonError.style.display = 'none';
            deleteModal.classList.add('active');
            setTimeout(() => reasonInput && reasonInput.focus(), 50);
        });

        deleteForm.addEventListener('submit', (e) => {
            if (!reasonInput || !reasonInput.value.trim()) {
                e.preventDefault();
                if (reasonError) reasonError.style.display = 'block';
                if (reasonInput) reasonInput.focus();
            }
        });

        deleteModal.querySelectorAll('[data-close-pet-delete]').forEach(el => {
            el.addEventListener('click', () => deleteModal.classList.remove('active'));
        });

        deleteModal.addEventListener('click', (e) => {
            if (e.target === deleteModal) deleteModal.classList.remove('active');
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') deleteModal.classList.remove('active');
        });
    }
});