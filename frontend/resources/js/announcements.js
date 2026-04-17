/**
 * BarangayPaws – Announcements Page JS
 * Handles modals for Create, Edit, and View details.
 */

document.addEventListener('DOMContentLoaded', () => {
    // ─── Modals ───
    const formModal = document.getElementById('form-modal');
    const viewModal = document.getElementById('view-modal');
    
    const formTitle = document.getElementById('form-modal-title');
    const formSubmitBtn = document.getElementById('form-submit-btn');

    // Overlay closes active modal
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.classList.remove('active');
            }
        });
    });

    // Close buttons
    document.querySelectorAll('[data-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.modal-overlay').classList.remove('active');
        });
    });

    // ─── Event Handlers ───
    
    // Create new
    const btnNew = document.getElementById('btn-new-announcement');
    if (btnNew) {
        btnNew.addEventListener('click', () => {
            formTitle.textContent = 'Post New Announcement';
            formSubmitBtn.textContent = 'Post';
            // Also here we would clear the form inputs
            formModal.classList.add('active');
        });
    }

    // Table click delegation
    document.addEventListener('click', (e) => {
        // Edit button
        const editBtn = e.target.closest('.action-icon-btn.edit');
        if (editBtn) {
            e.stopPropagation(); // don't trigger row click
            formTitle.textContent = 'Edit Announcement';
            formSubmitBtn.textContent = 'Save Changes';
            
            // Here we would populate inputs with row data
            const row = editBtn.closest('tr');
            document.getElementById('input-title').value = row.querySelector('.title-cell').textContent.trim();
            // ...
            
            formModal.classList.add('active');
            return;
        }

        // Delete button
        const deleteBtn = e.target.closest('.action-icon-btn.delete');
        if (deleteBtn) {
            e.stopPropagation();
            if(confirm("Are you sure you want to delete this announcement?")) {
                console.log("Deleted");
            }
            return;
        }

        // View details (Clicking view button or title)
        const viewBtn = e.target.closest('.action-icon-btn.view');
        const titleCell = e.target.closest('.title-cell');
        if (viewBtn || titleCell) {
            const row = (viewBtn || titleCell).closest('tr');
            
            // Populate view modal with data (mocked)
            document.getElementById('view-title').textContent = row.querySelector('.title-cell').textContent.trim();
            
            // Copy badge class and text
            const badge = row.querySelector('.cat-badge');
            const targetBadge = document.getElementById('view-badge');
            targetBadge.className = badge.className;
            targetBadge.textContent = badge.textContent;

            document.getElementById('view-target').textContent = row.cells[2].textContent.trim();
            document.getElementById('view-date').textContent = row.cells[3].textContent.trim();
            document.getElementById('view-loc').textContent = row.cells[4].textContent.trim();
            document.getElementById('view-posted').textContent = row.cells[5].textContent.trim();

            viewModal.classList.add('active');
        }
    });
});
