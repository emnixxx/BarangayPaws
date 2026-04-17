@vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/pets.css', 'resources/js/app.js', 'resources/js/pets.js'])
<x-app-layout>

<div class="dashboard-layout">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <div class="dashboard-main">

        {{-- Topbar --}}
        <header class="topbar">
            <h1 class="topbar-title">Pets</h1>
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

            {{-- Summary Filters --}}
            <div class="pets-summary-filters">
                <button class="summary-btn active">All <span class="badge">7</span></button>
                <button class="summary-btn">Cats <span class="badge">5</span></button>
                <button class="summary-btn">Dogs <span class="badge">2</span></button>
                <button class="summary-btn">Deceased <span class="badge">1</span></button>
            </div>

            {{-- Main Filters --}}
            <div class="pets-main-filters">
                <div class="pets-search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" id="pets-search" placeholder="Search by pet name or owner..." autocomplete="off">
                </div>
                <select class="filter-select"><option>Pet Type: All</option></select>
                <select class="filter-select"><option>Vaccination Status</option></select>
                <select class="filter-select"><option>Spayed Filter</option></select>
                <select class="filter-select"><option>Due treatment</option></select>
            </div>

            {{-- Pending Deceased Reports --}}
            <div class="pets-card pending-card">
                <div class="pets-card-header">
                    <h2 class="pets-card-title">Pending Deceased Reports <span class="title-badge">2</span></h2>
                </div>
                <table class="pets-table">
                    <thead>
                        <tr>
                            <th>Pet Name</th>
                            <th>Owner</th>
                            <th>Date of Death</th>
                            <th>Cause</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Bruno</td>
                            <td>Carlos Martinez</td>
                            <td>Apr 1, 2026</td>
                            <td>Old age</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-confirm">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Confirm
                                    </button>
                                    <button class="btn-reject">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Mittens</td>
                            <td>Isabel Cruz</td>
                            <td>Mar 30, 2026</td>
                            <td>Illness</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-confirm">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Confirm
                                    </button>
                                    <button class="btn-reject">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- All Pets --}}
            <div class="pets-card">
                <div class="pets-card-header">
                    <h2 class="pets-card-title">All Pets</h2>
                </div>
                <table class="pets-table" id="all-pets-table">
                    <thead>
                        <tr>
                            <th>Pet</th>
                            <th>Type & Breed</th>
                            <th>Owner Name</th>
                            <th>Vaccination</th>
                            <th>Deworming</th>
                            <th>Spayed</th>
                            <th>Status</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="pet-name-cell">
                                    <div class="pet-avatar"></div>
                                    <span class="pet-name">Max</span>
                                </div>
                            </td>
                            <td>
                                <div class="pet-info">
                                    <span class="pet-name">Dog</span>
                                    <span class="pet-breed">Labrador</span>
                                </div>
                            </td>
                            <td>Maria Santos</td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge approved">Approved</span></td>
                            <td>Jan 20, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-icon-btn view" title="View"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                    <button class="action-icon-btn edit" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                    <button class="action-icon-btn delete" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="pet-name-cell">
                                    <div class="pet-avatar"></div>
                                    <span class="pet-name">Whiskers</span>
                                </div>
                            </td>
                            <td>
                                <div class="pet-info">
                                    <span class="pet-name">Cat</span>
                                    <span class="pet-breed">Persian</span>
                                </div>
                            </td>
                            <td>Pedro Cruz</td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge no">No</span></td>
                            <td><span class="status-badge no">No</span></td>
                            <td><span class="status-badge approved">Approved</span></td>
                            <td>Feb 5, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-icon-btn view" title="View"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                    <button class="action-icon-btn edit" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                    <button class="action-icon-btn delete" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="pet-name-cell">
                                    <div class="pet-avatar"></div>
                                    <span class="pet-name">Buddy</span>
                                </div>
                            </td>
                            <td>
                                <div class="pet-info">
                                    <span class="pet-name">Dog</span>
                                    <span class="pet-breed">Beagle</span>
                                </div>
                            </td>
                            <td>Ana Reyes</td>
                            <td><span class="status-badge no">No</span></td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge pending">Pending</span></td>
                            <td>Mar 12, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-icon-btn view" title="View"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                    <button class="action-icon-btn edit" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                    <button class="action-icon-btn delete" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="pet-name-cell">
                                    <div class="pet-avatar"></div>
                                    <span class="pet-name">Luna</span>
                                </div>
                            </td>
                            <td>
                                <div class="pet-info">
                                    <span class="pet-name">Cat</span>
                                    <span class="pet-breed">Siamese</span>
                                </div>
                            </td>
                            <td>Jose Garcia</td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge approved">Approved</span></td>
                            <td>Jan 8, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-icon-btn view" title="View"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                    <button class="action-icon-btn edit" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                    <button class="action-icon-btn delete" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="pet-name-cell">
                                    <div class="pet-avatar"></div>
                                    <span class="pet-name">Charlie</span>
                                </div>
                            </td>
                            <td>
                                <div class="pet-info">
                                    <span class="pet-name">Dog</span>
                                    <span class="pet-breed">Poodle</span>
                                </div>
                            </td>
                            <td>Rosa Lopez</td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge no">No</span></td>
                            <td><span class="status-badge pending">Pending</span></td>
                            <td>Feb 28, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-icon-btn view" title="View"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                    <button class="action-icon-btn edit" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                    <button class="action-icon-btn delete" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="pet-name-cell">
                                    <div class="pet-avatar"></div>
                                    <span class="pet-name">Garfield</span>
                                </div>
                            </td>
                            <td>
                                <div class="pet-info">
                                    <span class="pet-name">Cat</span>
                                    <span class="pet-breed">Orange Tabby</span>
                                </div>
                            </td>
                            <td>Elena Cruz</td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge approved">Approved</span></td>
                            <td>Jan 15, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-icon-btn view" title="View"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                    <button class="action-icon-btn edit" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                    <button class="action-icon-btn delete" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="pet-name-cell">
                                    <div class="pet-avatar"></div>
                                    <span class="pet-name">Rocky</span>
                                </div>
                            </td>
                            <td>
                                <div class="pet-info">
                                    <span class="pet-name">Dog</span>
                                    <span class="pet-breed">German Shepherd</span>
                                </div>
                            </td>
                            <td>Roberto Lopez</td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge yes">Yes</span></td>
                            <td><span class="status-badge approved">Approved</span></td>
                            <td>Feb 18, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-icon-btn view" title="View"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                    <button class="action-icon-btn edit" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                    <button class="action-icon-btn delete" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="pagination">
                    <button class="page-btn">Previous</button>
                    <div class="page-num active">1</div>
                    <div class="page-num">2</div>
                    <button class="page-btn">Next</button>
                </div>
            </div>

        </main>
    </div>
</div>

</x-app-layout>
