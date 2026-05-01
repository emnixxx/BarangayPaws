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

            <div class="announcements-actions" style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap;">
                <div style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; height:38px; padding:0 14px; display:inline-flex; align-items:center; gap:8px; width:280px; box-sizing:border-box;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="announcements-search" placeholder="Search announcements..." autocomplete="off" style="border:none; outline:none; font-size:13px; flex:1; background:transparent; font-family:inherit; color:#374151; height:100%; line-height:1;">
                </div>
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
                    <tbody id="announcements-table-body">
                        @forelse($announcements as $announcement)
                            <tr>
                                <td style="cursor: pointer;">{{ $announcement->title }}</td>
                                <td><span class="cat-badge {{ $announcement->category }}">{{ str_replace('_', ' ', Str::title($announcement->category)) }}</span></td>
                                <td>{{ ucfirst($announcement->target_pet_type) }}</td>
                                <td>{{ $announcement->event_date ? \Carbon\Carbon::parse($announcement->event_date)->format('M j, Y') : 'N/A' }}</td>
                                <td>{{ $announcement->location }}</td>
                                <td>{{ $announcement->created_at->format('M j, Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon-btn edit" title="Edit Announcement">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                    </div>
                                    <div style="display:none;" class="announcement-details">{{ $announcement->details }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr id="no-announcements-row">
                                <td colspan="7" style="text-align:center; padding:40px; color:#9ca3af;">
                                    No announcements posted
                                </td>
                            </tr>
                        @endforelse
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

        <form id="announcement-form">
            <div class="form-group">
                <label class="form-label">Category</label>
                <select class="form-input" id="input-category" name="category" required>
                    <option value="libre_kapon">Libre Kapon</option>
                    <option value="vaccination">Vaccination</option>
                    <option value="deworming">Deworming</option>
                    <option value="spay_neuter">Spay/Neuter</option>
                    <option value="general">General</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Title</label>
                <input type="text" class="form-input" id="input-title" name="title" placeholder="Announcement title" required>
            </div>
            <div class="form-group">
                <label class="form-label">Date of Event</label>
                <input type="date" class="form-input" id="input-date" name="event_date">
            </div>
            <div class="form-group">
                <label class="form-label">Location</label>
                <input type="text" class="form-input" id="input-location" name="location" placeholder="Event location" required>
            </div>
            <div class="form-group">
                <label class="form-label">Details</label>
                <textarea class="form-input form-textarea" id="input-details" name="details" placeholder="Announcement details..." required></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Target Pet Type</label>
                <select class="form-input" id="input-target" name="target_pet_type" required>
                    <option value="dogs">Dogs</option>
                    <option value="cats">Cats</option>
                    <option value="other">Other/All</option>
                </select>
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

        <div class="view-modal-title" id="view-title"></div>
        <div class="view-modal-badge"><span class="cat-badge" id="view-badge"></span></div>

        <div class="view-details-grid">
            <div class="detail-item">
                <span class="detail-label">Target Pet Type</span>
                <span class="detail-val" id="view-target"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date of Event</span>
                <span class="detail-val" id="view-date"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Location</span>
                <span class="detail-val" id="view-loc"></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date Posted</span>
                <span class="detail-val" id="view-posted"></span>
            </div>
            <div class="detail-item detail-full">
                <span class="detail-label" style="margin-bottom:4px;">Details</span>
                <div class="detail-p" id="view-details-text"></div>
            </div>
        </div>

        <div class="modal-footer" style="margin-top:20px;">
            <button type="button" class="btn-submit btn-full" id="view-edit-btn">Edit Announcement</button>
        </div>
    </div>
</div>
{{-- Modal for Feedback --}}
<div class="modal-overlay" id="feedback-modal">
    <div class="modal-container feedback-modal" style="max-width: 400px; text-align: center;">
        <div class="modal-header" style="justify-content: flex-end; margin-bottom: 0;">
            <button class="modal-close" data-close><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
        </div>
        <div style="padding: 10px 20px 30px;">
            <h3 class="modal-title" id="feedback-title" style="margin-bottom: 15px;">Notification</h3>
            <p id="feedback-message" style="color: #4b5563; font-size: 15px;"></p>
        </div>
        <div class="modal-footer" style="justify-content: center;">
            <button type="button" class="btn-submit" data-close style="min-width: 120px;">OK</button>
        </div>
    </div>
</div>

</x-app-layout>