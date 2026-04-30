@vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/auditlog.css', 'resources/css/notifications.css', 'resources/js/app.js', 'resources/js/auditlog.js', 'resources/js/notifications.js'])<x-app-layout>

<div class="dashboard-layout">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <div class="dashboard-main">

    @include('partials.topbar', ['title' => 'Audit Logs'])

        {{-- Page Content --}}
        <main class="page-content">

            {{-- Filters --}}
            <div class="auditlog-filters">
                <div class="filters-left">
                    <button class="filter-btn" id="filter-action">
                        <span>Action Type: All</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <button class="filter-btn" id="filter-role">
                        <span>Role Filter</span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="search-box">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" id="searchInput" placeholder="Search logs...">
                    </div>
                </div>
                <button class="filter-btn" id="filter-date">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span>Last 30 Days</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
            </div>

            {{-- Table --}}
            <div class="auditlog-card">
                <table class="auditlog-table">
                    <thead>
                        <tr>
                            <th>DATE AND TIME</th>
                            <th>ACTION</th>
                            <th>PERFORMED BY</th>
                            <th>TARGET</th>
                            <th>DETAILS</th>
                        </tr>
                    </thead>
                    <tbody id="logTableBody">
                        {{-- Rows injected by auditlog.js --}}
                    </tbody>
                </table>

                {{-- Pagination --}}
                <div class="pagination">
                    <button class="page-btn" id="prevBtn">Previous</button>
                    <div class="page-numbers" id="pageNumbers"></div>
                    <button class="page-btn" id="nextBtn">Next</button>
                </div>
            </div>

        </main>
    </div>
</div>

<script>
    window.BackendAuditLogs = @json($logs);
</script>

</x-app-layout>