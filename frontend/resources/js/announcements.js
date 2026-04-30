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
    const announcementForm = document.getElementById('announcement-form');

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
            const overlay = btn.closest('.modal-overlay');
            if (overlay) overlay.classList.remove('active');
        });
    });

    // ─── Event Handlers ───
    
    // Create new
    const btnNew = document.getElementById('btn-new-announcement');
    if (btnNew) {
        btnNew.addEventListener('click', () => {
            formTitle.textContent = 'Post New Announcement';
            formSubmitBtn.textContent = 'Post';
            announcementForm.action = '/announcements';
            document.getElementById('form-method').value = 'POST';
            
            document.getElementById('input-title').value = '';
            document.getElementById('input-category').value = '';
            document.getElementById('input-target').value = 'other';
            document.getElementById('input-date').value = '';
            document.getElementById('input-location').value = '';
            document.getElementById('input-details').value = '';

            formModal.classList.add('active');
        });
    }

    // Table click delegation
    document.addEventListener('click', (e) => {
        // Edit button
        const editBtn = e.target.closest('.action-icon-btn.edit');
        if (editBtn) {
            e.stopPropagation();
            formTitle.textContent = 'Edit Announcement';
            formSubmitBtn.textContent = 'Save Changes';
            
            const row = editBtn.closest('tr');
            const id = row.querySelector('.title-cell').dataset.id;
            announcementForm.action = `/announcements/${id}`;
            document.getElementById('form-method').value = 'PUT';

            document.getElementById('input-title').value = row.querySelector('.title-cell').textContent.trim();
            document.getElementById('input-category').value = row.querySelector('.category-val').textContent.trim();
            
            let targetType = row.querySelector('.target-val').textContent.trim().toLowerCase();
            if (targetType === 'all' || targetType === 'all / other') targetType = 'other';
            document.getElementById('input-target').value = targetType;
            
            document.getElementById('input-date').value = row.querySelector('.event-date-val').dataset.raw || '';
            document.getElementById('input-location').value = row.querySelector('.location-val').textContent.trim();
            document.getElementById('input-details').value = row.querySelector('.details-cell').textContent.trim();
            
            formModal.classList.add('active');
            return;
        }

        // View details
        const viewBtn = e.target.closest('.action-icon-btn.view');
        if (viewBtn) {
            e.stopPropagation();
            const row = viewBtn.closest('tr');
            
            document.getElementById('view-title').textContent = row.querySelector('.title-cell').textContent.trim();
            
            const badge = row.querySelector('.cat-badge');
            const targetBadge = document.getElementById('view-badge');
            targetBadge.className = badge.className;
            targetBadge.textContent = badge.textContent;

            document.getElementById('view-target').textContent = row.querySelector('.target-val').textContent.trim();
            document.getElementById('view-date').textContent = row.querySelector('.event-date-val').textContent.trim();
            document.getElementById('view-loc').textContent = row.querySelector('.location-val').textContent.trim();
            document.getElementById('view-posted').textContent = row.querySelector('.posted-val').textContent.trim();
            document.getElementById('view-details-text').textContent = row.querySelector('.details-cell').textContent.trim();

            viewModal.classList.add('active');
        }
    });
});
