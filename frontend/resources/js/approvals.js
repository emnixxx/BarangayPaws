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

    // ─── Action handlers ───
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;

        if (btn.classList.contains('btn-approve')) {
            const row = btn.closest('tr');
            const name = row.querySelector('.info-name')?.textContent || 'this item';
            console.log(`Approved: ${name}`);
            // TODO: Send API request and remove row
            alert(`${name} has been approved.`);
            row.remove();
        } else if (btn.classList.contains('btn-reject')) {
            const row = btn.closest('tr');
            const name = row.querySelector('.info-name')?.textContent || 'this item';
            if (confirm(`Are you sure you want to reject ${name}?`)) {
                console.log(`Rejected: ${name}`);
                // TODO: Send API request and remove row
                row.remove();
            }
        }
    });
});
