@vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/auditlog.css', 'resources/js/app.js', 'resources/js/auditlog.js'])
<x-app-layout>

<div class="dashboard-layout">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <div class="dashboard-main">

        {{-- Topbar --}}
        <header class="topbar">
            <h1 class="topbar-title">Audit Logs</h1>
            <div class="topbar-right">
                <button type="button" class="topbar-icon-btn" title="Notifications">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    <span class="topbar-notif-dot"></span>
                </button>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="page-content">

            {{-- Filters --}}
            <div class="auditlog-filters">
                <div class="filters-left">
                    <div class="search-box">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        <input type="text" id="searchInput" placeholder="Search logs...">
                    </div>
                </div>

                <div class="filter-dropdown" data-filter="sort">
                    <button class="filter-btn" type="button">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M6 12h12M10 18h4"/></svg>
                        <span>Sort: <strong data-filter-label>Newest First</strong></span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="filter-menu filter-menu-right">
                        <button type="button" data-value="newest" data-label="Newest First" class="active">Newest First</button>
                        <button type="button" data-value="oldest" data-label="Oldest First">Oldest First</button>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="auditlog-card">
                <table class="auditlog-table">
                    <thead>
                        <tr>
                            <th>AUD ID</th>
                            <th>PERFORMED BY</th>
                            <th>TARGET</th>
                            <th>DETAILS</th>
                            <th>OLD STATUS</th>
                            <th>NEW STATUS</th>
                            <th>ACTION DATE</th>
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

            @php
                $auditLogsData = $logs->map(function($log) {
                    $createdAt = $log->created_at ? \Carbon\Carbon::parse($log->created_at) : ($log->audit_date ? \Carbon\Carbon::parse($log->audit_date) : now());
                    $status    = strtolower($log->status ?? 'pending');
                    $newStatus = strtolower($log->new_status ?? $status);
                    $notes     = $log->action_notes ?? '';
                    $notesLow  = strtolower($notes);

                    // ── Determine entity type & name ──────────────────────
                    $entityType = null;       // "Pet", "Report", "Health Record", "Resident", "Announcement"
                    $entityName = null;       // e.g. "Max"
                    $entityExtra = null;      // e.g. "Dog"

                    if ($log->pet_id) {
                        $entityType  = 'Pet';
                        $entityName  = optional($log->pet)->pet_name;
                        $entityExtra = optional($log->pet)->pet_type;
                    } elseif ($log->report_id) {
                        $entityType  = 'Report';
                        $entityName  = optional(optional($log->report)->pet)->pet_name;
                    } elseif ($log->record_id) {
                        $entityType  = 'Health Record';
                        $entityName  = optional(optional($log->record)->pet)->pet_name;
                    } elseif (str_contains($notesLow, 'resident')) {
                        $entityType = 'Resident';
                        if (preg_match('/resident[^:]*:\s*([^.\n]+?)\./i', $notes, $m)) {
                            $entityName = trim($m[1]);
                        }
                    } elseif (str_contains($notesLow, 'announcement')) {
                        $entityType = 'Announcement';
                        if (preg_match('/announcement[^:]*:\s*([^.\n]+?)\./i', $notes, $m)) {
                            $entityName = trim($m[1]);
                        }
                    } elseif (str_contains($notesLow, 'pet')) {
                        $entityType = 'Pet';
                    }

                    // ── Action label (e.g. "Pet Approved") ────────────────
                    $verbMap = [
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'deleted'  => 'Deleted',
                        'updated'  => 'Updated',
                        'posted'   => 'Posted',
                        'pending'  => 'Pending',
                    ];
                    $verb   = $verbMap[$newStatus] ?? ucfirst($newStatus);
                    $action = $entityType ? ($entityType . ' ' . $verb) : ($verb ?: 'System Action');

                    // ── Target column (just the entity name) ──────────────
                    $target = $entityName ?: ($entityType ?: 'System');

                    // ── Details (friendly sentence) ───────────────────────
                    if ($entityType && $entityName) {
                        $verbLower = strtolower($verb);
                        $extra = $entityExtra ? ' (' . ucfirst($entityExtra) . ')' : '';
                        $details = ucfirst($verbLower) . ' ' . strtolower($entityType) . ' registration for ' . $entityName . $extra . '.';
                    } else {
                        $details = $notes ?: '—';
                    }

                    // ── Badge color ───────────────────────────────────────
                    $badge = 'created';
                    if (in_array($status, ['rejected']) || in_array($newStatus, ['rejected','deleted'])) $badge = 'deleted';
                    elseif (in_array($status, ['approved'])) $badge = 'approved';

                    return [
                        'audit_id'    => $log->audit_id,
                        'timestamp'   => $createdAt->format('M j, Y h:i A'),
                        'created_iso' => $createdAt->toIso8601String(),
                        'action'      => $action,
                        'raw_action'  => strtolower($newStatus),
                        'badge'       => $badge,
                        'old_status'  => $log->old_status ? ucfirst($log->old_status) : '—',
                        'new_status'  => $log->new_status ? ucfirst($log->new_status) : '—',
                        'old_badge'   => strtolower($log->old_status ?? ''),
                        'new_badge'   => strtolower($log->new_status ?? ''),
                        'performer'   => optional($log->user)->user_name ?? 'Admin',
                        'role'        => ucfirst(optional($log->user)->role ?? 'System'),
                        'raw_role'    => strtolower(optional($log->user)->role ?? 'system'),
                        'target'      => $target,
                        'details'     => $details,
                    ];
                });
            @endphp
            <script>
                window.auditLogs = @json($auditLogsData);
            </script>
        </main>
    </div>
</div>

</x-app-layout>