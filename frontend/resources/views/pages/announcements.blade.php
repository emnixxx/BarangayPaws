@vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/announcements.css', 'resources/css/notifications.css', 'resources/js/app.js', 'resources/js/announcements.js', 'resources/js/notifications.js'])<x-app-layout>

<div class="dashboard-layout">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <div class="dashboard-main">

@include('partials.topbar', ['title' => 'Announcements'])
        {{-- Page Content --}}
        <main class="page-content">

            @if(session('success'))
                <div style="background:#d1fae5; color:#065f46; padding:10px 16px; border-radius:8px; margin-bottom:16px;">
                    {{ session('success') }}
                </div>
            @endif

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
                        @forelse($announcements as $announcement)
                            <tr>
                                <td class="title-cell" style="display:none;" data-id="{{ $announcement->announcement_id }}">{{ $announcement->title }}</td>
                                <td class="category-val" style="display:none;">{{ $announcement->category }}</td>
                                
                                <td>
                                    <div style="font-weight:600; color:#111827; cursor: pointer;" class="action-icon-btn view">{{ $announcement->title }}</div>
                                </td>
                                <td>
                                    <span class="cat-badge {{ $announcement->category }}">{{ ucwords(str_replace('_', ' ', $announcement->category)) }}</span>
                                </td>
                                <td class="target-val">{{ ucfirst($announcement->target_pet_type) }}</td>
                                <td class="event-date-val" data-raw="{{ $announcement->event_date }}">{{ $announcement->event_date ? \Carbon\Carbon::parse($announcement->event_date)->format('M j, Y') : 'N/A' }}</td>
                                <td class="location-val">{{ $announcement->location }}</td>
                                <td class="posted-val">{{ \Carbon\Carbon::parse($announcement->created_at)->format('M j, Y') }}</td>
                                <td style="display:none;" class="details-cell">{{ $announcement->details }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon-btn view" title="View Details">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </button>
                                        <button class="action-icon-btn edit" title="Edit Announcement">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <form method="POST" action="{{ route('announcements.destroy', $announcement->announcement_id) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-icon-btn delete" title="Delete Announcement" onclick="return confirm('Delete this announcement?')">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
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
            <button class="modal-close" data-close type="button"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
        </div>

        <form method="POST" action="{{ route('announcements.store') }}" id="announcement-form">
            @csrf
            <input type="hidden" name="_method" id="form-method" value="POST">
            
            <div class="form-group">
                <label class="form-label">Category</label>
                <select class="form-input" id="input-category" name="category" required>
                    <option value="" disabled selected>Select Category</option>
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
                <label class="form-label">Target Pet Type</label>
                <select class="form-input" id="input-target" name="target_pet_type" required>
                    <option value="other">All / Other</option>
                    <option value="dogs">Dogs</option>
                    <option value="cats">Cats</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Details</label>
                <textarea class="form-input form-textarea" id="input-details" name="details" placeholder="Announcement details..." required rows="4"></textarea>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-outline" data-close>Cancel</button>
                <button type="submit" class="btn-submit" id="form-submit-btn">Post</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal for Viewing Details --}}
<div class="modal-overlay" id="view-modal">
    <div class="modal-container view-modal">
        <div class="modal-header" style="margin-bottom:12px;">
            <h3 class="modal-title">Announcement Details</h3>
            <button class="modal-close" data-close type="button"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
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
            <div class="detail-item detail-full" style="grid-column: 1 / -1;">
                <span class="detail-label" style="margin-bottom:4px;">Details</span>
                <div class="detail-p" id="view-details-text" style="white-space: pre-wrap;"></div>
            </div>
        </div>

        <div class="modal-footer" style="margin-top:20px;">
            <button type="button" class="btn-outline btn-full" data-close>Close</button>
        </div>
    </div>
</div>

</x-app-layout>