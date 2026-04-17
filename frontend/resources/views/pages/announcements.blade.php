@vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/announcements.css', 'resources/js/app.js', 'resources/js/announcements.js'])
<x-app-layout>

<div class="dashboard-layout">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <div class="dashboard-main">

        {{-- Topbar --}}
        <header class="topbar">
            <h1 class="topbar-title">Announcements</h1>
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
            
            <div class="announcements-actions">
                <button class="btn-primary" id="btn-new-announcement">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Post Announcement
                </button>
            </div>

            <div class="announcements-card">
                <table class="announcements-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Target Pet Type</th>
                            <th>Date of Event</th>
                            <th>Location</th>
                            <th>Date Posted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="title-cell">Free Kapon Program for April</td>
                            <td><span class="cat-badge kapon">Libre Kapon</span></td>
                            <td class="light-text">Dogs & Cats</td>
                            <td class="light-text">Apr 15, 2026</td>
                            <td class="light-text">Barangay Health Center</td>
                            <td class="light-text">Apr 1, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-icon-btn view" title="View"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                    <button class="action-icon-btn edit" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                    <button class="action-icon-btn delete" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="title-cell">Anti-Rabies Vaccination Drive</td>
                            <td><span class="cat-badge vax">Vaccination</span></td>
                            <td class="light-text">All Pets</td>
                            <td class="light-text">Apr 20, 2026</td>
                            <td class="light-text">Barangay Hall</td>
                            <td class="light-text">Mar 28, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-icon-btn view" title="View"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                    <button class="action-icon-btn edit" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                    <button class="action-icon-btn delete" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="title-cell">Deworming Schedule for Q2</td>
                            <td><span class="cat-badge deworm">Deworming</span></td>
                            <td class="light-text">Dogs & Cats</td>
                            <td class="light-text">Apr 25, 2026</td>
                            <td class="light-text">Barangay Health Center</td>
                            <td class="light-text">Mar 25, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-icon-btn view" title="View"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                    <button class="action-icon-btn edit" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                    <button class="action-icon-btn delete" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="title-cell">Pet Registration Reminder</td>
                            <td><span class="cat-badge general">General</span></td>
                            <td class="light-text">All Pets</td>
                            <td class="light-text">Ongoing</td>
                            <td class="light-text">Online/Barangay Hall</td>
                            <td class="light-text">Mar 20, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-icon-btn view" title="View"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                    <button class="action-icon-btn edit" title="Edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></button>
                                    <button class="action-icon-btn delete" title="Delete"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="title-cell">Spay and Neuter Benefits Seminar</td>
                            <td><span class="cat-badge spay">Spay-Neuter</span></td>
                            <td class="light-text">Pet Owners</td>
                            <td class="light-text">Apr 10, 2026</td>
                            <td class="light-text">Barangay Hall</td>
                            <td class="light-text">Mar 15, 2026</td>
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
            </div>

        </main>
    </div>
</div>

{{-- Modal for Create & Edit --}}
<div class="modal-overlay" id="form-modal">
    <div class="modal-container form-modal">
        <div class="modal-header">
            <h3 class="modal-title" id="form-modal-title">Post New Announcement</h3>
            <button class="modal-close" data-close><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
        </div>
        
        <form>
            <div class="form-group">
                <label class="form-label">Category</label>
                <input type="text" class="form-input" id="input-category">
            </div>
            <div class="form-group">
                <label class="form-label">Title</label>
                <input type="text" class="form-input" id="input-title" placeholder="Announcement title">
            </div>
            <div class="form-group">
                <label class="form-label">Date & Time</label>
                <input type="text" class="form-input" id="input-date">
            </div>
            <div class="form-group">
                <label class="form-label">Location</label>
                <input type="text" class="form-input" id="input-location" placeholder="Event location">
            </div>
            <div class="form-group">
                <label class="form-label">Details</label>
                <textarea class="form-input form-textarea" id="input-details" placeholder="Announcement details..."></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Target Pet Type</label>
                <input type="text" class="form-input" id="input-target">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-outline" data-close>Cancel</button>
                <button type="button" class="btn-submit" id="form-submit-btn">Post</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal for Viewing Details --}}
<div class="modal-overlay" id="view-modal">
    <div class="modal-container view-modal">
        <div class="modal-header" style="margin-bottom:12px;">
            <h3 class="modal-title">Announcement Details</h3>
            <button class="modal-close" data-close><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
        </div>

        <div class="view-modal-title" id="view-title">Free Kapon Program for April</div>
        <div class="view-modal-badge"><span class="cat-badge" id="view-badge">Libre Kapon</span></div>

        <div class="view-details-grid">
            <div class="detail-item">
                <span class="detail-label">Target Pet Type</span>
                <span class="detail-val" id="view-target">Dogs & Cats</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date of Event</span>
                <span class="detail-val" id="view-date">Apr 15, 2026</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Location</span>
                <span class="detail-val" id="view-loc">Barangay Health Center</span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date Posted</span>
                <span class="detail-val" id="view-posted">Apr 1, 2026</span>
            </div>
            <div class="detail-item detail-full">
                <span class="detail-label" style="margin-bottom:4px;">Details</span>
                <div class="detail-p">
                    Free spay and neuter services for dogs and cats. Bring your pet registration and health records.
                </div>
            </div>
        </div>

        <div class="modal-footer" style="margin-top:20px;">
            <button type="button" class="btn-outline btn-full" data-close>Close</button>
        </div>
    </div>
</div>

</x-app-layout>
