/**
 * BarangayPaws – Approvals Page JS
 * Handles tab switching and action buttons.
 */

document.addEventListener('DOMContentLoaded', () => {
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    // ─── Tab Switching ───
    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const targetId = btn.dataset.target;
            
            // Update buttons
            tabBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            // Update content
            tabContents.forEach(content => {
                if (content.id === targetId) {
                    content.classList.add('active');
                } else {
                    content.classList.remove('active');
                }
            });
        });
    });

    // ─── Search ───
    const searchInput = document.getElementById('approvals-search');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const q = searchInput.value.toLowerCase().trim();
            document.querySelectorAll('.tab-content table tbody tr').forEach(row => {
                if (!q) { row.style.display = ''; return; }
                const text = (row.textContent || '').toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    }

    // Action handlers removed to allow action-modals.js to handle the actual form submission
});
