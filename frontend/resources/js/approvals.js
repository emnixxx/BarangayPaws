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

    // ─── Photo Lightbox ───
    const lightboxModal       = document.getElementById('photo-lightbox-modal');
    const lightboxCurrentImg  = document.getElementById('lightbox-current-img');
    const lightboxCurrentEmpty = document.getElementById('lightbox-current-empty');
    const lightboxNewImg      = document.getElementById('lightbox-new-img');
    const closeLightboxBtn    = document.getElementById('close-lightbox');

    if (lightboxModal) {
        const closeLightbox = () => lightboxModal.classList.remove('active');

        document.addEventListener('click', (e) => {
            // Open lightbox on new-photo thumbnail click
            const thumb = e.target.closest('[data-lightbox-new]');
            if (thumb) {
                const newSrc     = thumb.dataset.lightboxNew;
                const currentSrc = thumb.dataset.currentPhoto;
                const name       = thumb.dataset.residentName || 'Resident';

                document.getElementById('lightbox-title').textContent = `${name} — Photo Comparison`;

                lightboxNewImg.src = newSrc;

                if (currentSrc) {
                    lightboxCurrentImg.src = currentSrc;
                    lightboxCurrentImg.style.display = 'block';
                    lightboxCurrentEmpty.style.display = 'none';
                } else {
                    lightboxCurrentImg.style.display = 'none';
                    lightboxCurrentEmpty.style.display = 'flex';
                }

                lightboxModal.classList.add('active');
                return;
            }

            // Close on X button or backdrop click
            if (e.target.closest('#close-lightbox') || e.target === lightboxModal) {
                closeLightbox();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeLightbox();
        });
    }
});
