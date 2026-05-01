// ===== Audit log data (populated from backend) =====
const auditLogs = window.auditLogs || [];

// ===== Config =====
const ROWS_PER_PAGE = 12;
let currentPage = 1;
let filteredLogs = [...auditLogs];

// ===== Filter state =====
const activeFilters = {
    sort:   'newest',
    search: '',
};

// ===== Apply filters =====
function applyFilters() {
    const q = activeFilters.search.toLowerCase();

    filteredLogs = auditLogs.filter(log => {
        if (q) {
            const hay = `${log.action} ${log.performer} ${log.target} ${log.details}`.toLowerCase();
            if (!hay.includes(q)) return false;
        }
        return true;
    });

    // Sort
    filteredLogs.sort((a, b) => {
        const ta = a.created_iso ? new Date(a.created_iso).getTime() : 0;
        const tb = b.created_iso ? new Date(b.created_iso).getTime() : 0;
        return activeFilters.sort === 'oldest' ? ta - tb : tb - ta;
    });

    currentPage = 1;
    renderTable();
    renderPagination();
}

// ===== Render table rows =====
function renderTable() {
    const tbody = document.getElementById('logTableBody');
    if (!tbody) return;

    const start = (currentPage - 1) * ROWS_PER_PAGE;
    const end = start + ROWS_PER_PAGE;
    const pageData = filteredLogs.slice(start, end);

    if (pageData.length === 0) {
        tbody.innerHTML = `<tr class="empty-row"><td colspan="7">No logs found</td></tr>`;
        return;
    }

    const statusBadge = (val, raw) => {
        if (!val || val === '—') return '<span class="text-muted">—</span>';
        let cls = 'created';
        if (raw === 'rejected' || raw === 'deleted') cls = 'deleted';
        else if (raw === 'approved') cls = 'approved';
        else if (raw === 'pending') cls = 'pending';
        return `<span class="audit-badge ${cls}">${val}</span>`;
    };

    tbody.innerHTML = pageData.map(log => `
        <tr>
            <td><strong>#${log.audit_id}</strong></td>
            <td>
                <div class="performer">
                    <strong>${log.performer}</strong>
                    <span>${log.role}</span>
                </div>
            </td>
            <td>${log.target}</td>
            <td>${log.details}</td>
            <td>${statusBadge(log.old_status, log.old_badge)}</td>
            <td>${statusBadge(log.new_status, log.new_badge)}</td>
            <td>${log.timestamp}</td>
        </tr>
    `).join('');
}

// ===== Render pagination =====
function renderPagination() {
    const totalPages = Math.ceil(filteredLogs.length / ROWS_PER_PAGE) || 1;
    const pageNumbers = document.getElementById('pageNumbers');
    if (!pageNumbers) return;

    let html = '';
    for (let i = 1; i <= totalPages; i++) {
        html += `<button class="page-num ${i === currentPage ? 'active' : ''}" data-page="${i}">${i}</button>`;
    }
    pageNumbers.innerHTML = html;

    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = currentPage === totalPages;

    document.querySelectorAll('.page-num').forEach(btn => {
        btn.addEventListener('click', () => {
            currentPage = parseInt(btn.dataset.page);
            renderTable();
            renderPagination();
        });
    });
}

// ===== Init (run immediately if DOM ready, else wait) =====
function initAuditLog() {
    const searchInput = document.getElementById('searchInput');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            activeFilters.search = e.target.value;
            applyFilters();
        });
    }

    // ── Filter dropdowns ──
    document.querySelectorAll('.filter-dropdown').forEach(dropdown => {
        const key       = dropdown.dataset.filter;
        const btn       = dropdown.querySelector('.filter-btn');
        const labelEl   = dropdown.querySelector('[data-filter-label]');
        const menuItems = dropdown.querySelectorAll('.filter-menu button');

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            // close other dropdowns
            document.querySelectorAll('.filter-dropdown.open').forEach(d => {
                if (d !== dropdown) d.classList.remove('open');
            });
            dropdown.classList.toggle('open');
        });

        menuItems.forEach(item => {
            item.addEventListener('click', () => {
                menuItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                activeFilters[key] = item.dataset.value;
                if (labelEl) labelEl.textContent = item.dataset.label || item.textContent;
                dropdown.classList.remove('open');
                applyFilters();
            });
        });
    });

    // Close dropdowns on outside click
    document.addEventListener('click', () => {
        document.querySelectorAll('.filter-dropdown.open').forEach(d => d.classList.remove('open'));
    });

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
                renderPagination();
            }
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            const totalPages = Math.ceil(filteredLogs.length / ROWS_PER_PAGE);
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
                renderPagination();
            }
        });
    }

    renderTable();
    renderPagination();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAuditLog);
} else {
    initAuditLog();
}