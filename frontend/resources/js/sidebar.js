// sidebar.js — BarangayPaws Sidebar interactions

document.addEventListener('DOMContentLoaded', function () {

    // Active nav item based on current URL
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll('.sidebar-nav-item');

    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && currentPath.includes(href) && href !== '/') {
            item.classList.add('active');
        } else if (href === '/' && currentPath === '/') {
            item.classList.add('active');
        }
    });

    // Logout button confirmation
    const logoutBtn = document.getElementById('sidebar-logout');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
            if (confirm('Are you sure you want to log out?')) {
                window.location.href = '/logout';
            }
        });
    }

    // Mobile sidebar toggle (for responsive layout)
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('sidebar--open');
        });

        // Close sidebar when clicking outside (mobile)
        document.addEventListener('click', function (e) {
            if (
                sidebar.classList.contains('sidebar--open') &&
                !sidebar.contains(e.target) &&
                e.target !== sidebarToggle
            ) {
                sidebar.classList.remove('sidebar--open');
            }
        });
    }

});