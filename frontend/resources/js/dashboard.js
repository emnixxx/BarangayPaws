// dashboard.js — BarangayPaws Dashboard

document.addEventListener('DOMContentLoaded', function () {

    // ─── Stats (populated from backend) ────────────
    const stats = window.BackendStats || {
        totalPets: 0,pendingApprovals: 0,vaccinated: 0,deceased: 0
    };
    const registrations = window.BackendRegistrations || { labels: [], data: [] };
    const petTypes = window.BackendPetTypes || { dogs: 0, cats: 0 };
    const recentPets = window.BackendRecentPets || [];
    const auditLogs = window.BackendAuditLogs || [];

    // ─── Render Stat Cards ────────────────────────────────────────
    const statCards = document.querySelectorAll('[data-stat]');
    statCards.forEach(card => {
        const key = card.dataset.stat;
        if (stats[key] !== undefined) {
            const el = card.querySelector('[data-value]');
            if (el) animateCount(el, stats[key]);
        }
    });

    function animateCount(el, target) {
        let start = 0;
        const duration = 900;
        const step = (timestamp) => {
            if (!start) start = timestamp;
            const progress = Math.min((timestamp - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target).toLocaleString();
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    }

    // ─── Registration Chart (Line) ────────────────────────────────
    const regCtx = document.getElementById('registrationChart');
    if (regCtx && window.Chart) {
        new Chart(regCtx, {
            type: 'line',
            data: {
                labels: registrations.labels,
                datasets: [{
                    data: registrations.data,
                    borderColor: '#2d8a5e',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#2d8a5e',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    backgroundColor: function(ctx) {
                        const canvas = ctx.chart.ctx;
                        const gradient = canvas.createLinearGradient(0, 0, 0, 220);
                        gradient.addColorStop(0, 'rgba(45,138,94,0.18)');
                        gradient.addColorStop(1, 'rgba(45,138,94,0.01)');
                        return gradient;
                    },
                    tension: 0.4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#90afa3' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#e3ede8' },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#90afa3' }
                    }
                }
            }
        });
    }

    // ─── Pet Type Donut Chart ─────────────────────────────────────
    const donutCtx = document.getElementById('petTypeChart');
    if (donutCtx && window.Chart) {
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: ['Dog', 'Cat'],
                datasets: [{
                    data: [petTypes.dogs, petTypes.cats],
                    backgroundColor: ['#1a3a2a', '#e07030'],
                    borderWidth: 0,
                    hoverOffset: 6,
                }]
            },
            options: {
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.label}: ${ctx.parsed}%`
                        }
                    }
                }
            }
        });
    }

    // ─── Rate Bars ────────────────────────────────────────────────
    setTimeout(() => {
        const bars = document.querySelectorAll('[data-rate]');
        bars.forEach(bar => {
            const val = bar.dataset.rate;
            bar.style.width = val + '%';
        });
    }, 200);

    // ─── Render Recent Pets Table ─────────────────────────────────
    const petTableBody = document.getElementById('pet-table-body');
    if (petTableBody) {
        if (recentPets.length === 0) {
            petTableBody.innerHTML = `<tr><td colspan="4" style="text-align:center; padding:24px; color:#9ca3af;">No recent registrations</td></tr>`;
        } else {
            petTableBody.innerHTML = recentPets.map(pet => `
                <tr>
                    <td>
                        <div class="pet-name">${pet.name}</div>
                        <div class="pet-owner">${pet.owner}</div>
                    </td>
                    <td>${pet.type}</td>
                    <td><span class="badge ${pet.status}">${capitalize(pet.status)}</span></td>
                    <td>${pet.date}</td>
                </tr>
            `).join('');
        }
    }

    // ─── Render Audit Logs ────────────────────────────────────────
    const auditList = document.getElementById('audit-list');
    if (auditList) {
        if (auditLogs.length === 0) {
            auditList.innerHTML = `<div style="text-align:center; padding:24px; color:#9ca3af;">No recent activity</div>`;
        } else {
            auditList.innerHTML = auditLogs.map(log => `
                <div class="audit-item">
                    <div class="audit-icon-wrap">
                        <span class="audit-badge ${log.type}">${log.label}</span>
                    </div>
                    <div>
                        <div class="audit-desc">${log.desc}</div>
                        <div class="audit-time">${log.time}</div>
                    </div>
                </div>
            `).join('');
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

});