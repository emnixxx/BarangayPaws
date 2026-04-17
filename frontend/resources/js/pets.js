/**
 * BarangayPaws – Pets Page JS
 * Handles search, filters, and pet actions.
 */

document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('pets-search');
    const tableBody   = document.querySelector('#all-pets-table tbody');

    if (!searchInput || !tableBody) return;

    // ─── Live search ───
    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase().trim();
        const rows  = tableBody.querySelectorAll('tr');

        rows.forEach(row => {
            const petName  = row.querySelector('.pet-name')?.textContent.toLowerCase() || '';
            const owner    = row.cells[2]?.textContent.toLowerCase() || '';
            
            const matches = petName.includes(query) || owner.includes(query);
            row.style.display = matches ? '' : 'none';
        });
    });

    // ─── Action handlers ───
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;

        if (btn.classList.contains('btn-confirm')) {
            console.log('Confirm pending report');
        } else if (btn.classList.contains('btn-reject')) {
            console.log('Reject pending report');
        } else if (btn.classList.contains('delete')) {
            const pet = btn.closest('tr').querySelector('.pet-name')?.textContent;
            if (confirm(`Remove ${pet} from the system?`)) {
                console.log('Delete pet');
            }
        }
    });

    // ─── Summary filters ───
    const summaryBtns = document.querySelectorAll('.summary-btn');
    summaryBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            summaryBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            // TODO: Filter table data based on selection
        });
    });
});
