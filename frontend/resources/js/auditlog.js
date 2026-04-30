// ===== Audit log data (populated from backend) =====
const auditLogs = window.BackendAuditLogs || [];

// ===== Config =====
const ROWS_PER_PAGE = 12;
let currentPage = 1;
let filteredLogs = [...auditLogs];

// ===== Render table rows =====
function renderTable() {
    const tbody = document.getElementById('logTableBody');
    if (!tbody) return;

    const start = (currentPage - 1) * ROWS_PER_PAGE;
    const end = start + ROWS_PER_PAGE;
    const pageData = filteredLogs.slice(start, end);

    if (pageData.length === 0) {
        tbody.innerHTML = `<tr class="empty-row"><td colspan="5">No logs found</td></tr>`;
        return;
    }

    tbody.innerHTML = pageData.map(log => `
        <tr>
            <td>${log.timestamp}</td>
            <td><span class="audit-badge ${log.badge}">${log.action}</span></td>
            <td>
                <div class="performer">
                    <strong>${log.performer}</strong>
                    <span>${log.role}</span>
                </div>
            </td>
            <td>${log.target}</td>
            <td>${log.details}</td>
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

// ===== Init (wait for DOM) =====
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase();
            filteredLogs = auditLogs.filter(log =>
                log.action.toLowerCase().includes(query) ||
                log.performer.toLowerCase().includes(query) ||
                log.target.toLowerCase().includes(query) ||
                log.details.toLowerCase().includes(query)
            );
            currentPage = 1;
            renderTable();
            renderPagination();
        });
    }

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
});