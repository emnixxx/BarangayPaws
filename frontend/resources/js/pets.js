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
            const type = row.dataset.type;
            const status = row.dataset.status;
            const name = row.dataset.name || '';
            const owner = row.dataset.owner || '';

            let filterMatch = true;
            if (currentFilter === 'cat') filterMatch = (type === 'cat');
            else if (currentFilter === 'dog') filterMatch = (type === 'dog');
            else if (currentFilter === 'deceased') filterMatch = (status === 'deceased');

            const searchMatch = !query || name.includes(query) || owner.includes(query);
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

    // View modal
    petsTable.addEventListener('click', (e) => {
        const viewBtn = e.target.closest('.action-icon-btn.view');
        if (!viewBtn) return;

        const row = viewBtn.closest('tr');
        if (!row) return;

        const d = row.dataset;

        document.getElementById('modal-pet-name').textContent = d.petName || '—';
        document.getElementById('modal-pet-type').textContent = d.petType ? (d.petType.charAt(0).toUpperCase() + d.petType.slice(1)) : '—';
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

        const healthBadge = (label, filled) => ({
            text: label + ': ' + (filled ? 'Yes' : 'No'),
            className: 'status-badge ' + (filled ? 'yes' : 'no'),
        });

        const vac = healthBadge('Vaccinated', d.vaccinated === '1');
        const dew = healthBadge('Dewormed', d.dewormed === '1');
        const spa = healthBadge('Spayed/Neutered', d.spayed === '1');

        const vacEl = document.getElementById('modal-vaccinated');
        const dewEl = document.getElementById('modal-dewormed');
        const spaEl = document.getElementById('modal-spayed');

        vacEl.textContent = vac.text; vacEl.className = vac.className;
        dewEl.textContent = dew.text; dewEl.className = dew.className;
        spaEl.textContent = spa.text; spaEl.className = spa.className;

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

    // Close view modal
    document.querySelectorAll('[data-close-view]').forEach(btn => {
        btn.addEventListener('click', () => viewModal.classList.remove('active'));
    });

    if (viewModal) {
        viewModal.addEventListener('click', (e) => {
            if (e.target === viewModal) viewModal.classList.remove('active');
        });
    }
});