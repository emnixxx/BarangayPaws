@vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/approvals.css', 'resources/css/modals.css', 'resources/js/app.js', 'resources/js/action-modals.js', 'resources/js/approvals.js'])
<x-app-layout>

<div class="dashboard-layout">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <div class="dashboard-main">

        {{-- Topbar --}}
        <header class="topbar">
            <h1 class="topbar-title">Approvals</h1>
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

            {{-- Success Flash --}}
            @if(session('success'))
                <div style="background:#d1fae5; color:#065f46; padding:10px 16px; border-radius:8px; margin-bottom:16px;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tabs + Search --}}
            <div class="approvals-toolbar" style="display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:16px;">
                <div class="approvals-tabs" style="margin-bottom:0;">
                    <button class="tab-btn active" data-target="residents-tab">
                        Resident <span class="badge">{{ $pendingResidents->count() }}</span>
                    </button>
                    <button class="tab-btn" data-target="pets-tab">
                        Pets <span class="badge">{{ $pendingPets->count() }}</span>
                    </button>
                    <button class="tab-btn" data-target="photos-tab">
                        Profile Photos <span class="badge" id="photos-tab-badge">{{ $pendingPhotoRequests->count() }}</span>
                    </button>
                </div>
                <div class="search-box" style="background:#fff; border:1px solid #e5e7eb; border-radius:8px; height:38px; padding:0 14px; display:inline-flex; align-items:center; gap:8px; width:280px; box-sizing:border-box;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" id="approvals-search" placeholder="Search approvals..." autocomplete="off" style="border:none; outline:none; font-size:13px; flex:1; background:transparent; font-family:inherit; color:#374151; height:100%; line-height:1;">
                </div>
            </div>

            {{-- 1: Residents Pending Approvals --}}
            <div class="approvals-card tab-content active" id="residents-tab">
                <div class="approvals-card-header">
                    <h2 class="approvals-card-title">Pending Resident Registrations</h2>
                </div>

                <table class="approvals-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Date Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingResidents as $resident)
                            <tr>
                                <td>
                                    <div class="info-cell">
                                        <div class="resident-avatar">
                                            {{ strtoupper(substr($resident->user_name, 0, 2)) }}
                                        </div>
                                        <span class="info-name">{{ $resident->user_name }}</span>
                                    </div>
                                </td>
                                <td class="email-col">{{ $resident->email }}</td>
                                <td>{{ $resident->contact_num }}</td>
                                <td>{{ \Carbon\Carbon::parse($resident->date_registered)->format('M j, Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn-approve"
                                            data-action-confirm
                                            data-url="{{ route('approvals.resident.approve', $resident->user_id) }}"
                                            data-title="Approve Resident"
                                            data-message="Are you sure you want to approve this user?">
                                            <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            Approve
                                        </button>
                                        <button type="button" class="btn-reject"
                                            data-action-reject
                                            data-url="{{ route('approvals.resident.reject', $resident->user_id) }}"
                                            data-title="Reject Resident"
                                            data-message="Please provide a reason for rejecting this user.">
                                            <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:40px; color:#9ca3af;">
                                    No pending resident registrations
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 2: Pets Pending Approvals --}}
            <div class="approvals-card tab-content" id="pets-tab">
                <div class="approvals-card-header">
                    <h2 class="approvals-card-title" style="color: var(--text-primary);">
                        Pending Pet Registrations <span class="title-badge">{{ $pendingPets->count() }}</span>
                    </h2>
                </div>

                <table class="approvals-table">
                    <thead>
                        <tr>
                            <th>Pet</th>
                            <th>Type & Breed</th>
                            <th>Owner Name</th>
                            <th>Contact</th>
                            <th>Date Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingPets as $pet)
                            <tr>
                                <td>
                                    <div class="info-cell">
                                        @include('partials.pet-icon', ['type' => $pet->pet_type])
                                        <span class="info-name">{{ $pet->pet_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="info-name">{{ ucfirst($pet->pet_type) }}</span>
                                    <span class="info-sub" style="color: var(--blue);">{{ $pet->breed }}</span>
                                </td>
                                <td>{{ $pet->owner->user_name ?? 'N/A' }}</td>
                                <td>{{ $pet->owner->contact_num ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($pet->registered_at)->format('M j, Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn-approve"
                                            data-action-confirm
                                            data-url="{{ route('approvals.pet.approve', $pet->pet_id) }}"
                                            data-title="Approve Pet"
                                            data-message="Are you sure you want to approve this pet?">
                                            <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            Approve
                                        </button>
                                        <button type="button" class="btn-reject"
                                            data-action-reject
                                            data-url="{{ route('approvals.pet.reject', $pet->pet_id) }}"
                                            data-title="Reject Pet"
                                            data-message="Please provide a reason for rejecting this pet.">
                                            <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:40px; color:#9ca3af;">
                                    No pending pet registrations
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- 3: Profile Photo Requests --}}
            <div class="approvals-card tab-content" id="photos-tab">
                <div class="approvals-card-header">
                    <h2 class="approvals-card-title">
                        Pending Profile Photo Requests
                        <span class="title-badge">{{ $pendingPhotoRequests->count() }}</span>
                    </h2>
                </div>

                <table class="approvals-table">
                    <thead>
                        <tr>
                            <th>Resident</th>
                            <th>Current Photo</th>
                            <th>New Photo</th>
                            <th>Date Requested</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingPhotoRequests as $photoReq)
                            <tr>
                                {{-- Resident --}}
                                <td>
                                    <div class="info-cell">
                                        <div class="resident-avatar">
                                            {{ strtoupper(substr($photoReq->resident->user_name ?? '?', 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="info-name">{{ $photoReq->resident->user_name ?? 'Unknown' }}</span>
                                            <span class="info-sub">{{ $photoReq->resident->email ?? '' }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Current Photo --}}
                                <td>
                                    @if($photoReq->current_photo_path)
                                        <img src="{{ asset('storage/' . $photoReq->current_photo_path) }}"
                                             alt="Current photo"
                                             class="photo-thumb">
                                    @else
                                        <div class="photo-thumb-placeholder">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                                <circle cx="12" cy="7" r="4"/>
                                            </svg>
                                            <span>No photo</span>
                                        </div>
                                    @endif
                                </td>

                                {{-- New Photo (clickable → lightbox) --}}
                                <td>
                                    <img src="{{ asset('storage/' . $photoReq->new_photo_path) }}"
                                         alt="New photo"
                                         class="photo-thumb photo-thumb-new"
                                         title="Click to preview side-by-side"
                                         data-lightbox-new="{{ asset('storage/' . $photoReq->new_photo_path) }}"
                                         data-current-photo="{{ $photoReq->current_photo_path ? asset('storage/' . $photoReq->current_photo_path) : '' }}"
                                         data-resident-name="{{ $photoReq->resident->user_name ?? 'Resident' }}">
                                </td>

                                {{-- Date Requested --}}
                                <td>{{ $photoReq->requested_at->format('M j, Y') }}</td>

                                {{-- Actions --}}
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn-approve"
                                            data-action-confirm
                                            data-url="{{ route('approvals.photo.approve', $photoReq->id) }}"
                                            data-title="Approve Photo"
                                            data-message="Are you sure you want to approve this profile photo change?">
                                            <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            Approve
                                        </button>
                                        <button type="button" class="btn-reject"
                                            data-action-reject
                                            data-url="{{ route('approvals.photo.reject', $photoReq->id) }}"
                                            data-title="Reject Photo"
                                            data-message="Optionally provide a reason for rejecting this photo change.">
                                            <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:40px; color:#9ca3af;">
                                    No pending profile photo requests
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

{{-- Photo Lightbox Modal --}}
<div class="modal-overlay" id="photo-lightbox-modal">
    <div class="modal-container" style="max-width: 680px;">
        <div class="modal-header">
            <h3 class="modal-title" id="lightbox-title">Photo Comparison</h3>
            <button class="modal-close" id="close-lightbox">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="photo-comparison">
            <div class="photo-comparison-side">
                <div class="photo-comparison-label">Current Photo</div>
                <img id="lightbox-current-img" src="" alt="Current photo" class="photo-comparison-img" style="display:none;">
                <div class="photo-thumb-placeholder photo-comparison-empty" id="lightbox-current-empty">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>No current photo</span>
                </div>
            </div>
            <div class="photo-comparison-arrow">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </div>
            <div class="photo-comparison-side">
                <div class="photo-comparison-label photo-comparison-label-new">New Photo</div>
                <img id="lightbox-new-img" src="" alt="New photo" class="photo-comparison-img">
            </div>
        </div>
    </div>
</div>

@include('partials.action-modals')

</x-app-layout>