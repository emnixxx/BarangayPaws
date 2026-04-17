/**
 * BarangayPaws – Residents Page JS
 * Handles search filtering and table interactions.
 */

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('residents-search');
    const tableBody   = document.getElementById('residents-table-body');

    if (!searchInput || !tableBody) return;

    // ─── Live search / filter ───
    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase().trim();
        const rows  = tableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const name    = row.querySelector('.resident-fullname')?.textContent.toLowerCase() || '';
            const email   = row.cells[1]?.textContent.toLowerCase() || '';
            const phone   = row.cells[2]?.textContent.toLowerCase() || '';
            const address = row.cells[3]?.textContent.toLowerCase() || '';

            const matches = name.includes(query) || email.includes(query) || phone.includes(query) || address.includes(query);
            row.style.display = matches ? '' : 'none';
        });
    });

    // ─── View button handler (placeholder) ───
    tableBody.addEventListener('click', (e) => {
        const viewBtn = e.target.closest('.view-btn');
        if (viewBtn) {
            const residentId = viewBtn.dataset.id;
            console.log('View resident:', residentId);
            // TODO: Open resident details modal or navigate
        }

        const deleteBtn = e.target.closest('.delete-btn');
        if (deleteBtn) {
            const residentId = deleteBtn.dataset.id;
            const residentName = deleteBtn.closest('tr').querySelector('.resident-fullname')?.textContent || '';
            if (confirm(`Are you sure you want to remove ${residentName}?`)) {
                console.log('Delete resident:', residentId);
                // TODO: Send delete request to backend
            }
        }
    });
});
