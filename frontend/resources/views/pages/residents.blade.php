
@vite(['resources/css/app.css', 'resources/css/residents.css', 'resources/css/notifications.css', 'resources/js/app.js', 'resources/js/residents.js', 'resources/js/notifications.js'])
<x-app-layout>

<div class="dashboard-layout">

    @include('partials.sidebar')

    <div class="dashboard-main">

     @include('partials.topbar', ['title' => 'Residents'])

        <main class="page-content">

            @if(session('success'))
                <div style="background:#d1fae5; color:#065f46; padding:10px 16px; border-radius:8px; margin-bottom:16px;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Search Bar --}}
            <div class="residents-search-bar">
                <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="residents-search" placeholder="Search residents..." autocomplete="off">
            </div>

            {{-- All Residents --}}
            <div class="residents-card">
                <div class="residents-card-header">
                    <h2 class="residents-card-title">Approved Residents ({{ $residents->count() }})</h2>
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
                        @forelse($residents as $resident)
                            <tr
                                data-id="{{ $resident->user_id }}"
                                data-name="{{ $resident->user_name }}"
                                data-email="{{ $resident->email }}"
                                data-gender="{{ $resident->gender }}"
                                data-contact="{{ $resident->contact_num }}"
                                data-address="{{ $resident->address }}"
                                data-joined="{{ \Carbon\Carbon::parse($resident->date_registered)->format('M j, Y') }}"
                                data-pets='@json($resident->pets ?? [])'
                            >
                                <td>
                                    <div class="resident-name-cell">
                                        <div class="resident-avatar">
                                            {{ strtoupper(substr($resident->user_name, 0, 2)) }}
                                        </div>
                                        <span class="resident-fullname">{{ $resident->user_name }}</span>
                                    </div>
                                </td>
                                <td>{{ $resident->email }}</td>
                                <td>{{ $resident->contact_num }}</td>
                                <td>{{ $resident->address }}</td>
                                <td>
                                    @if($resident->status === 'approved')
                                        <span class="status-badge approved">Approved</span>
                                    @elseif($resident->status === 'pending')
                                        <span class="status-badge pending">Pending</span>
                                    @elseif($resident->status === 'rejected')
                                        <span class="status-badge rejected" title="{{ $resident->rejection_reason }}">Rejected</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($resident->date_registered)->format('M j, Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn view-btn" title="View">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </button>

                                        @if($resident->status !== 'approved')
                                            <button type="button" class="action-btn approve-btn" title="Approve"
                                                data-type="resident"
                                                data-id="{{ $resident->user_id }}"
                                                data-name="{{ $resident->user_name }}">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            </button>
                                        @endif

                                        @if($resident->status !== 'rejected')
                                            <button type="button" class="action-btn reject-btn" title="Reject"
                                                data-type="resident"
                                                data-id="{{ $resident->user_id }}"
                                                data-name="{{ $resident->user_name }}">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            </button>
                                        @endif

                                        <form method="POST" action="{{ route('residents.destroy', $resident->user_id) }}" style="display:inline;" onsubmit="return confirm('Delete {{ $resident->user_name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete-btn" title="Delete">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"/>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:40px; color:#9ca3af;">
                                    No residents found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

{{-- View Details Modal --}}
<div class="modal-overlay" id="resident-view-modal">
    <div class="modal-container" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="modal-title">Resident Details</h3>
            <button class="modal-close" data-close>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div style="display:flex; align-items:center; gap:14px; margin-bottom:20px;">
            <div class="resident-avatar" id="modal-avatar" style="width:54px; height:54px; font-size:18px;"></div>
            <div>
                <div id="modal-name" style="font-size:18px; font-weight:700; color:#111827;"></div>
                <span class="status-badge active">Active Resident</span>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px 24px; margin-bottom:20px;">
            <div>
                <div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Email</div>
                <div id="modal-email" style="font-size:14px; color:#374151;"></div>
            </div>
            <div>
                <div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Gender</div>
                <div id="modal-gender" style="font-size:14px; color:#374151;"></div>
            </div>
            <div>
                <div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Contact Number</div>
                <div id="modal-contact" style="font-size:14px; color:#374151;"></div>
            </div>
            <div>
                <div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Date Joined</div>
                <div id="modal-joined" style="font-size:14px; color:#374151;"></div>
            </div>
            <div style="grid-column:1 / -1;">
                <div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Address</div>
                <div id="modal-address" style="font-size:14px; color:#374151;"></div>
            </div>
        </div>

        <div style="border-top:1px solid #e5e7eb; padding-top:16px;">
            <h4 style="font-size:14px; font-weight:700; color:#111827; margin-bottom:10px;">
                Registered Pets (<span id="modal-pets-count">0</span>)
            </h4>
            <div id="modal-pets-list" style="display:flex; flex-direction:column; gap:8px;"></div>
        </div>
    </div>
</div>

{{-- Approve Confirmation Modal --}}
<div class="modal-overlay" id="approve-confirm-modal">
    <div class="modal-container" style="max-width: 440px;">
        <div class="modal-header">
            <h3 class="modal-title">Approve Resident</h3>
            <button type="button" class="modal-close" data-close>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <p class="confirm-modal-text">
            Are you sure you want to approve <strong id="approve-name"></strong>?
        </p>
        <form id="approve-form" method="POST">
            @csrf
            <div class="confirm-modal-actions">
                <button type="button" class="btn-reject" data-close>Cancel</button>
                <button type="submit" class="btn-approve">Confirm Approve</button>
            </div>
        </form>
    </div>
</div>

{{-- Reject Confirmation Modal --}}
<div class="modal-overlay" id="reject-confirm-modal">
    <div class="modal-container" style="max-width: 480px;">
        <div class="modal-header">
            <h3 class="modal-title">Reject Resident</h3>
            <button type="button" class="modal-close" data-close>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <p class="confirm-modal-text">
            Are you sure you want to reject <strong id="reject-name"></strong>?
        </p>
        <form id="reject-form" method="POST">
            @csrf
            <label for="rejection_reason" class="confirm-modal-label">
                Reject Reason <span class="confirm-required">*</span>
            </label>
            <textarea id="rejection_reason" name="rejection_reason" required rows="3"
                class="confirm-modal-textarea"
                placeholder="Enter reason for rejection..."></textarea>
            <div class="confirm-modal-actions">
                <button type="button" class="btn-approve" data-close>Cancel</button>
                <button type="submit" class="btn-reject">Confirm Reject</button>
            </div>
        </form>
    </div>
</div>

</x-app-layout>