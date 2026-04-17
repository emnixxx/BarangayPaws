@vite(['resources/css/app.css', 'resources/css/residents.css', 'resources/js/app.js', 'resources/js/residents.js'])
<x-app-layout>

<div class="dashboard-layout">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <div class="dashboard-main">

        {{-- Topbar --}}
        <header class="topbar">
            <h1 class="topbar-title">Residents</h1>
            <div class="topbar-right">
                <button class="topbar-icon-btn" title="Notifications">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    <span class="topbar-notif-dot"></span>
                </button>
                <div class="topbar-avatar" title="{{ auth()->user()->name ?? 'Juan Dela Cruz' }}">
                    {{ strtoupper(substr(auth()->user()->name ?? 'JD', 0, 2)) }}
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="page-content">

            {{-- Search Bar --}}
            <div class="residents-search-bar">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="residents-search" placeholder="Search residents..." autocomplete="off">
            </div>

            {{-- Residents Table Card --}}
            <div class="residents-card">
                <div class="residents-card-header">
                    <h2 class="residents-card-title">All Residents</h2>
                </div>

                <table class="residents-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Date Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="residents-table-body">
                        <tr>
                            <td>
                                <div class="resident-name-cell">
                                    <div class="resident-avatar" style="background:#1a3a2a; border-color:#4caf7d;">JDC</div>
                                    <span class="resident-fullname">Juan Dela Cruz</span>
                                </div>
                            </td>
                            <td>juan.delacruz@email.com</td>
                            <td>0917-111-2222</td>
                            <td>123 Main St, Brgy. San Jose</td>
                            <td><span class="status-badge active">Active</span></td>
                            <td>Jan 15, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view-btn" data-id="1" title="View">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete-btn" data-id="1" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="resident-name-cell">
                                    <div class="resident-avatar" style="background:#2d8a5e; border-color:#4caf7d;">RL</div>
                                    <span class="resident-fullname">Rosa Lopez</span>
                                </div>
                            </td>
                            <td>rosa.lopez@email.com</td>
                            <td>0918-222-3333</td>
                            <td>456 Oak Ave, Brgy. San Jose</td>
                            <td><span class="status-badge active">Active</span></td>
                            <td>Jan 20, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view-btn" data-id="2" title="View">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete-btn" data-id="2" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="resident-name-cell">
                                    <div class="resident-avatar" style="background:#e07030; border-color:#e07030;">JG</div>
                                    <span class="resident-fullname">Jose Garcia</span>
                                </div>
                            </td>
                            <td>jose.garcia@email.com</td>
                            <td>0919-333-4444</td>
                            <td>789 Pine Rd, Brgy. San Jose</td>
                            <td><span class="status-badge active">Active</span></td>
                            <td>Feb 3, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view-btn" data-id="3" title="View">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete-btn" data-id="3" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="resident-name-cell">
                                    <div class="resident-avatar" style="background:#7c5cbf; border-color:#7c5cbf;">CR</div>
                                    <span class="resident-fullname">Carmen Reyes</span>
                                </div>
                            </td>
                            <td>carmen.reyes@email.com</td>
                            <td>0920-444-5555</td>
                            <td>321 Elm St, Brgy. San Jose</td>
                            <td><span class="status-badge active">Active</span></td>
                            <td>Feb 18, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view-btn" data-id="4" title="View">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete-btn" data-id="4" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="resident-name-cell">
                                    <div class="resident-avatar" style="background:#2e7bbf; border-color:#2e7bbf;">MS</div>
                                    <span class="resident-fullname">Miguel Santos</span>
                                </div>
                            </td>
                            <td>miguel.santos@email.com</td>
                            <td>0921-555-6666</td>
                            <td>654 Birch Ln, Brgy. San Jose</td>
                            <td><span class="status-badge active">Active</span></td>
                            <td>Mar 5, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view-btn" data-id="5" title="View">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete-btn" data-id="5" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="resident-name-cell">
                                    <div class="resident-avatar" style="background:#1a9e8e; border-color:#1a9e8e;">EC</div>
                                    <span class="resident-fullname">Elena Cruz</span>
                                </div>
                            </td>
                            <td>elena.cruz@email.com</td>
                            <td>0922-666-7777</td>
                            <td>987 Maple Dr, Brgy. San Jose</td>
                            <td><span class="status-badge active">Active</span></td>
                            <td>Mar 12, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view-btn" data-id="6" title="View">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete-btn" data-id="6" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div class="resident-name-cell">
                                    <div class="resident-avatar" style="background:#1a3a2a; border-color:#4caf7d;">RL</div>
                                    <span class="resident-fullname">Roberto Lopez</span>
                                </div>
                            </td>
                            <td>roberto.lopez@email.com</td>
                            <td>0923-777-8888</td>
                            <td>147 Cedar Ct, Brgy. San Jose</td>
                            <td><span class="status-badge active">Active</span></td>
                            <td>Mar 25, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view-btn" data-id="7" title="View">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </button>
                                    <button class="action-btn delete-btn" data-id="7" title="Delete">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

</x-app-layout>
