// dashboard.js — BarangayPaws Dashboard with dummy data

document.addEventListener('DOMContentLoaded', function () {

    // ─── Dummy Data ───────────────────────────────────────────────
    const dummyStats = {
        totalPets: 1234,
        pendingApprovals: 23,
        vaccinated: 987,
        deceased: 45,
    };

    const dummyRegistrations = {
        labels: ['Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
        data: [160, 175, 195, 245, 270, 310],
    };

    const dummyPetTypes = {
        dogs: 60,
        cats: 40,
    };

    const dummyRates = {
        vaccination: 80,
        deworming: 65,
        spayed: 45,
    };

    const dummyRecentPets = [
        { name: 'Max',     owner: 'Maria Santos',  type: 'Dog', status: 'approved', date: 'Apr 7, 2026' },
        { name: 'Whiskers',owner: 'Pedro Cruz',    type: 'Cat', status: 'approved', date: 'Apr 7, 2026' },
        { name: 'Buddy',   owner: 'Ana Reyes',     type: 'Dog', status: 'pending',  date: 'Apr 6, 2026' },
        { name: 'Luna',    owner: 'Jose Garcia',   type: 'Cat', status: 'approved', date: 'Apr 6, 2026' },
        { name: 'Charlie', owner: 'Rosa Lopez',    type: 'Dog', status: 'pending',  date: 'Apr 5, 2026' },
    ];

    const dummyAuditLogs = [
        { type: 'pet-approved', label: 'Pet Approved',       desc: 'Max registration approved by Admin',   time: '2 hours ago' },
        { type: 'account',      label: 'Account Created',    desc: 'New resident Maria Santos registered',  time: '3 hours ago' },
        { type: 'deceased',     label: 'Deceased Confirmed', desc: 'Bruno marked as deceased',              time: '5 hours ago' },
        { type: 'announcement', label: 'Announcement Posted',desc: 'Libre Kapon event scheduled',           time: '1 day ago' },
        { type: 'pet-approved', label: 'Pet Approved',       desc: 'Luna registration approved by Admin',   time: '1 day ago' },
    ];

    // ─── Render Stat Cards ────────────────────────────────────────
    const statCards = document.querySelectorAll('[data-stat]');
    statCards.forEach(card => {
        const key = card.dataset.stat;
        if (dummyStats[key] !== undefined) {
            const el = card.querySelector('[data-value]');
            if (el) animateCount(el, dummyStats[key]);
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
                labels: dummyRegistrations.labels,
                datasets: [{
                    data: dummyRegistrations.data,
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
                plugins: { legend: { display: false }, tooltip: { mode: 'index', intersect: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', size: 11 }, color: '#90afa3' }
                    },
                    y: {
                        beginAtZero: false,
                        min: 80,
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
                    data: [dummyPetTypes.dogs, dummyPetTypes.cats],
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
        petTableBody.innerHTML = dummyRecentPets.map(pet => `
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

    // ─── Render Audit Logs ────────────────────────────────────────
    const auditList = document.getElementById('audit-list');
    if (auditList) {
        auditList.innerHTML = dummyAuditLogs.map(log => `
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

    // ─── Helpers ─────────────────────────────────────────────────
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

});