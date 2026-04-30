@vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/notifications.css', 'resources/js/app.js', 'resources/js/dashboard.js', 'resources/js/notifications.js'])
<x-app-layout>

<div class="dashboard-layout">

    @include('partials.sidebar')

    <div class="dashboard-main">

        @include('partials.topbar', ['title' => 'Dashboard'])

        <main class="page-content">

            {{-- ── Stat Cards ── --}}
            <div class="stat-grid">
                <div class="stat-card" data-stat="totalPets">
                    <div class="stat-card-top">
                        <span class="stat-card-label">Total Pets</span>
                        <div class="stat-card-icon green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="7" r="2"/><circle cx="15" cy="7" r="2"/>
                                <circle cx="6" cy="13" r="2"/><circle cx="18" cy="13" r="2"/>
                                <ellipse cx="12" cy="17" rx="3" ry="2.5"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value green" data-value>0</div>
                </div>

                <div class="stat-card" data-stat="pendingApprovals">
                    <div class="stat-card-top">
                        <span class="stat-card-label">Pending Approvals</span>
                        <div class="stat-card-icon orange">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <line x1="12" y1="8" x2="12" y2="12"/>
                                <line x1="12" y1="16" x2="12.01" y2="16"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value orange" data-value>0</div>
                </div>

                <div class="stat-card" data-stat="vaccinated">
                    <div class="stat-card-top">
                        <span class="stat-card-label">Vaccinated</span>
                        <div class="stat-card-icon blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 2l4 4-14 14H4v-4L18 2z"/>
                                <line x1="14" y1="6" x2="18" y2="10"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value blue" data-value>0</div>
                </div>

                <div class="stat-card" data-stat="deceased">
                    <div class="stat-card-top">
                        <span class="stat-card-label">Deceased</span>
                        <div class="stat-card-icon purple">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="stat-card-value purple" data-value>0</div>
                </div>
            </div>

            <div class="charts-row">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Pet Registrations Over Time</span>
                        <a href="{{ route('residents') }}" class="card-action">View all</a>
                    </div>
                    <div style="position:relative; flex:1; width:100%; min-height:100px;">
                        <canvas id="registrationChart"></canvas>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Pet Type Distribution</span>
                    </div>
                    <div class="donut-wrapper">
                        <div style="position:relative; width:180px; height:180px;">
                            <canvas id="petTypeChart"></canvas>
                            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;line-height:1.2">
                                <div style="font-size:11px;color:var(--text-muted);font-weight:500;">Total</div>
                                <div style="font-size:22px;font-weight:700;color:var(--text-primary);">{{ $stats['totalPets'] }}</div>
                            </div>
                        </div>
                        <div class="donut-legend">
                            <div class="legend-item">
                                <div class="legend-dot" style="background:#1a3a2a"></div>
                                Dog ({{ $totalPets > 0 ? round(($petTypes['dogs'] / $totalPets) * 100) : 0 }}%)
                            </div>
                            <div class="legend-item">
                                <div class="legend-dot" style="background:#e07030"></div>
                                Cat ({{ $totalPets > 0 ? round(($petTypes['cats'] / $totalPets) * 100) : 0 }}%)
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rates-row">
                <div class="rate-card">
                    <div class="rate-label">Vaccination Rate</div>
                    <div class="rate-value green">{{ $rates['vaccination'] }}%</div>
                    <div class="rate-bar-track">
                        <div class="rate-bar-fill green" data-rate="{{ $rates['vaccination'] }}" style="width:0%"></div>
                    </div>
                    <div class="rate-sub">{{ $rawCounts['vaccinated'] }} out of {{ $totalPets }} pets</div>
                </div>

                <div class="rate-card">
                    <div class="rate-label">Deworming Rate</div>
                    <div class="rate-value teal">{{ $rates['deworming'] }}%</div>
                    <div class="rate-bar-track">
                        <div class="rate-bar-fill teal" data-rate="{{ $rates['deworming'] }}" style="width:0%"></div>
                    </div>
                    <div class="rate-sub">{{ $rawCounts['dewormed'] }} out of {{ $totalPets }} pets</div>
                </div>

                <div class="rate-card">
                    <div class="rate-label">Spayed / Neutered Rate</div>
                    <div class="rate-value purple">{{ $rates['spayed'] }}%</div>
                    <div class="rate-bar-track">
                        <div class="rate-bar-fill purple" data-rate="{{ $rates['spayed'] }}" style="width:0%"></div>
                    </div>
                    <div class="rate-sub">{{ $rawCounts['spayed'] }} out of {{ $totalPets }} pets</div>
                </div>
            </div>

            <div class="bottom-row">
                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Recent Pet Registrations</span>
                        <a href="{{ route('pets') }}" class="card-action">View all</a>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Pet / Owner</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="pet-table-body"></tbody>
                    </table>
                </div>

                <div class="card">
                    <div class="card-header">
                        <span class="card-title">Recent Audit Logs</span>
                        <a href="{{ route('auditlog')}}" class="card-action">View all</a>
                    </div>
                    <div class="audit-list" id="audit-list"></div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    window.BackendStats = @json($stats);
    window.BackendRegistrations = @json($registrations);
    window.BackendPetTypes = @json($petTypes);
    window.BackendRecentPets = @json($recentPets);
    window.BackendAuditLogs = @json($auditLogs);
</script>

</x-app-layout>