/**
 * BarangayPaws – Announcements Page JS
 * Handles modals for Create, Edit, and View details.
 */

document.addEventListener('DOMContentLoaded', () => {
    // ─── Search ───
    const searchInput = document.getElementById('announcements-search');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const q = searchInput.value.toLowerCase().trim();
            const tbody = document.getElementById('announcements-table-body');
            if (!tbody) return;
            tbody.querySelectorAll('tr').forEach(row => {
                if (row.id === 'no-announcements-row') return;
                if (!q) { row.style.display = ''; return; }
                const text = (row.textContent || '').toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    }

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

    // ─── Helper function for modals ───
    function showFeedbackModal(title, message) {
        let fbModal = document.getElementById('feedback-modal');
        if (!fbModal) {
            // fallback if it doesn't exist on page
            alert(message);
            return;
        }
        document.getElementById('feedback-title').textContent = title;
        document.getElementById('feedback-message').textContent = message;
        fbModal.classList.add('active');
    }

    // ─── Event Handlers ───
    
    let currentActiveRow = null;

    const btnNew = document.getElementById('btn-new-announcement');
    if (btnNew) {
        btnNew.addEventListener('click', () => {
            formTitle.textContent = 'Post New Announcement';
            formSubmitBtn.textContent = 'Post';
            document.getElementById('announcement-form').reset();
            formModal.classList.add('active');
        });
    }

    if (formSubmitBtn) formSubmitBtn.addEventListener('click', (e) => {
        e.preventDefault();
        const form = document.getElementById('announcement-form');
        if (!form.reportValidity()) return;

        const formData = new FormData(form);
        fetch('/announcements', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const a = data.announcement;
                const tbody = document.getElementById('announcements-table-body');
                
                // remove no announcements row if exists
                const noRow = document.getElementById('no-announcements-row');
                if (noRow) noRow.remove();

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="cursor: pointer;">${a.title}</td>
                    <td><span class="cat-badge ${a.category}">${a.category.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</span></td>
                    <td>${a.target_pet_type.charAt(0).toUpperCase() + a.target_pet_type.slice(1)}</td>
                    <td>${a.event_date}</td>
                    <td>${a.location}</td>
                    <td>${a.date_posted}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="action-icon-btn edit" title="Edit Announcement">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                        </div>
                        <div style="display:none;" class="announcement-details">${a.details}</div>
                    </td>
                `;
                tbody.insertBefore(tr, tbody.firstChild);
                formModal.classList.remove('active');
                showFeedbackModal('Success', 'Announcement created successfully!');
            } else {
                showFeedbackModal('Error', 'Error creating announcement');
            }
        })
        .catch(err => {
            console.error(err);
            showFeedbackModal('Error', 'An error occurred while creating announcement.');
        });
    });

    // Table click delegation
    document.addEventListener('click', (e) => {


        // Delete button
        const deleteBtn = e.target.closest('.action-icon-btn.delete');
        if (deleteBtn) {
            e.stopPropagation();
            if(confirm("Are you sure you want to delete this announcement?")) {
                console.log("Deleted");
            }
            return;
        }

        // Edit button from View Modal
        if (e.target.id === 'view-edit-btn') {
            if (currentActiveRow) {
                viewModal.classList.remove('active');
                
                formTitle.textContent = 'Edit Announcement';
                formSubmitBtn.textContent = 'Save Changes';
                
                document.getElementById('input-title').value = currentActiveRow.cells[0].textContent.trim();
                
                const badgeClass = currentActiveRow.querySelector('.cat-badge').className;
                const category = badgeClass.replace('cat-badge', '').trim();
                document.getElementById('input-category').value = category;

                const targetType = currentActiveRow.cells[2].textContent.trim().toLowerCase();
                const targetOptions = Array.from(document.getElementById('input-target').options).map(opt => opt.value);
                const matchedType = targetOptions.find(opt => targetType.includes(opt));
                document.getElementById('input-target').value = matchedType || 'other';

                // We don't have exact event date value in YYYY-MM-DD from the table easily,
                // but we can try to parse it or leave it blank for now as it's mocked in UI.
                document.getElementById('input-date').value = '';
                
                document.getElementById('input-location').value = currentActiveRow.cells[4].textContent.trim();
                
                const detailsText = currentActiveRow.querySelector('.announcement-details').textContent.trim();
                document.getElementById('input-details').value = detailsText;
                
                formModal.classList.add('active');
            }
            return;
        }


        // Edit button in table row
        const editBtn = e.target.closest('.action-icon-btn.edit');
        if (editBtn) {
            e.stopPropagation(); // don't trigger row click
            currentActiveRow = editBtn.closest('tr');
            
            formTitle.textContent = 'Edit Announcement';
            formSubmitBtn.textContent = 'Save Changes';
            
            document.getElementById('input-title').value = currentActiveRow.cells[0].textContent.trim();
            
            const badgeClass = currentActiveRow.querySelector('.cat-badge').className;
            const category = badgeClass.replace('cat-badge', '').trim();
            document.getElementById('input-category').value = category;

            const targetType = currentActiveRow.cells[2].textContent.trim().toLowerCase();
            const targetOptions = Array.from(document.getElementById('input-target').options).map(opt => opt.value);
            const matchedType = targetOptions.find(opt => targetType.includes(opt));
            document.getElementById('input-target').value = matchedType || 'other';

            document.getElementById('input-date').value = '';
            document.getElementById('input-location').value = currentActiveRow.cells[4].textContent.trim();
            
            const detailsText = currentActiveRow.querySelector('.announcement-details').textContent.trim();
            document.getElementById('input-details').value = detailsText;
            
            formModal.classList.add('active');
            return;
        }

        // View details (Clicking the row, excluding action buttons)
        const row = e.target.closest('tr');
        const actionButtons = e.target.closest('.action-buttons');
        
        if (row && row.id !== 'no-announcements-row' && !actionButtons && row.closest('#announcements-table-body')) {
            // Populate view modal with data
            document.getElementById('view-title').textContent = row.cells[0].textContent.trim();
            
            // Copy badge class and text
            const badge = row.querySelector('.cat-badge');
            const targetBadge = document.getElementById('view-badge');
            targetBadge.className = badge.className;
            targetBadge.textContent = badge.textContent;

            document.getElementById('view-target').textContent = row.cells[2].textContent.trim();
            document.getElementById('view-date').textContent = row.cells[3].textContent.trim();
            document.getElementById('view-loc').textContent = row.cells[4].textContent.trim();
            document.getElementById('view-posted').textContent = row.cells[5].textContent.trim();

            const detailsText = row.querySelector('.announcement-details').textContent.trim();
            document.getElementById('view-details-text').textContent = detailsText;
            
            currentActiveRow = row;

            viewModal.classList.add('active');
        }
    });
});
