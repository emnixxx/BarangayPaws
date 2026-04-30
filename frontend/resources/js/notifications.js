/**
 * BarangayPaws - Global Notification Poller
 * Polls pending counts every 3s. If new pending items detected, plays sound + auto-refresh.
 */

document.addEventListener('DOMContentLoaded', () => {
    const POLL_INTERVAL = 3000;
    const STORAGE_KEY = 'bp_last_pending_total';

    const bell = document.getElementById('notif-bell');
    const dot = document.getElementById('notif-dot');
    const countEl = document.getElementById('notif-count');
    const dropdown = document.getElementById('notif-dropdown');
    const list = document.getElementById('notif-list');
    const sound = document.getElementById('notif-sound');

    if (!bell) return;

    // Toggle dropdown
    bell.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('active');
    });

    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target) && e.target !== bell) {
            dropdown.classList.remove('active');
        }
    });

    const playSound = () => {
        try {
            sound.currentTime = 0;
            sound.play().catch(() => {});
        } catch (e) {}
    };

    const updateUI = (data) => {
        const total = data.total || 0;

        // Sidebar approvals badge
        const sidebarBadge = document.getElementById('sidebar-approvals-badge');
        if (sidebarBadge) {
            if (total > 0) {
                sidebarBadge.style.display = 'inline-block';
                sidebarBadge.textContent = total > 99 ? '99+' : total;
            } else {
                sidebarBadge.style.display = 'none';
            }
        }

        if (total > 0) {
            dot.style.display = 'block';
            countEl.style.display = 'flex';
            countEl.textContent = total > 99 ? '99+' : total;

            let html = '';
            if (data.residents > 0) {
                html += `
                    <a href="/approvals" class="notif-item">
                        <div class="notif-icon blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        </div>
                        <div class="notif-content">
                            <div class="notif-title">${data.residents} Pending Resident${data.residents > 1 ? 's' : ''}</div>
                            <div class="notif-sub">Click to review</div>
                        </div>
                    </a>
                `;
            }
            if (data.pets > 0) {
                html += `
                    <a href="/approvals" class="notif-item">
                        <div class="notif-icon orange">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="7" r="2"/><circle cx="15" cy="7" r="2"/><circle cx="6" cy="13" r="2"/><circle cx="18" cy="13" r="2"/><ellipse cx="12" cy="17" rx="3" ry="2.5"/></svg>
                        </div>
                        <div class="notif-content">
                            <div class="notif-title">${data.pets} Pending Pet${data.pets > 1 ? 's' : ''}</div>
                            <div class="notif-sub">Click to review</div>
                        </div>
                    </a>
                `;
            }
            list.innerHTML = html;
        } else {
            dot.style.display = 'none';
            countEl.style.display = 'none';
            list.innerHTML = '<div class="notif-empty">No new notifications</div>';
        }
    };

    const checkPending = async () => {
        try {
            const res = await fetch('/notifications/pending-count', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            if (!res.ok) return;

            const data = await res.json();
            const currentTotal = data.total || 0;
            const lastTotal = parseInt(sessionStorage.getItem(STORAGE_KEY) || '-1', 10);

            updateUI(data);

            // First-time load: just store baseline
            if (lastTotal === -1) {
                sessionStorage.setItem(STORAGE_KEY, currentTotal);
                return;
            }

            // New pending item detected
            if (currentTotal > lastTotal) {
                sessionStorage.setItem(STORAGE_KEY, currentTotal);
                playSound();
                setTimeout(() => window.location.reload(), 800);
                return;
            }

            sessionStorage.setItem(STORAGE_KEY, currentTotal);
        } catch (e) {
            console.error('Notification poll error:', e);
        }
    };

    checkPending();
    setInterval(checkPending, POLL_INTERVAL);
});