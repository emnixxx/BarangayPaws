@vite(['resources/css/app.css', 'resources/css/residents.css', 'resources/css/modals.css', 'resources/js/app.js', 'resources/js/residents.js'])
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
                    <h2 class="residents-card-title">All Residents ({{ $residents->count() }})</h2>
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
                                        <div class="resident-avatar" style="background:#1a3a2a; border-color:#4caf7d;">
                                            {{ strtoupper(substr($resident->user_name, 0, 2)) }}
                                        </div>
                                        <span class="resident-fullname">{{ $resident->user_name }}</span>
                                    </div>
                                </td>
                                <td>{{ $resident->email }}</td>
                                <td>{{ $resident->contact_num }}</td>
                                <td>{{ $resident->address }}</td>
                                <td><span class="status-badge active">Active</span></td>
                                <td>{{ \Carbon\Carbon::parse($resident->date_registered)->format('M j, Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn view-btn" data-id="{{ $resident->user_id }}" title="View">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </button>
                                        <button type="button"
                                                class="action-btn delete-btn"
                                                title="Delete"
                                                data-delete-btn
                                                data-name="{{ $resident->user_name }}"
                                                data-action="{{ route('residents.destroy', $resident->user_id) }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </button>
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

{{-- ─── View Details Modal ─── --}}
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
            <div class="resident-avatar" id="modal-avatar" style="background:#1a3a2a; border-color:#4caf7d; width:54px; height:54px; font-size:18px;"></div>
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

        {{-- Pets section --}}
        <div style="border-top:1px solid #e5e7eb; padding-top:16px;">
            <h4 style="font-size:14px; font-weight:700; color:#111827; margin-bottom:10px;">
                Registered Pets (<span id="modal-pets-count">0</span>)
            </h4>
            <div id="modal-pets-list" style="display:flex; flex-direction:column; gap:8px;">
                {{-- Injected by JS --}}
            </div>
        </div>

    </div>
</div>

{{-- ─── Delete Confirmation Modal ─── --}}
<div class="modal-overlay" id="resident-delete-modal">
    <div class="modal-container" style="max-width: 420px;">
        <div class="modal-header">
            <h3 class="modal-title">Delete Resident</h3>
            <button class="modal-close" data-close-delete>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div style="display:flex; align-items:flex-start; gap:14px; margin-bottom:20px;">
            <div style="width:42px; height:42px; border-radius:50%; background:#fee2e2; color:#b91c1c; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <div>
                <p style="font-size:14px; color:#374151; margin:0 0 6px; line-height:1.5;">
                    Are you sure you want to delete <strong id="delete-resident-name" style="color:#111827;"></strong>?
                </p>
                <p style="font-size:12.5px; color:#6b7280; margin:0; line-height:1.5;">
                    This action cannot be undone. All data tied to this resident will be permanently removed.
                </p>
            </div>
        </div>

        <form method="POST" id="delete-resident-form">
            @csrf
            @method('DELETE')

            <div style="margin-bottom:18px;">
                <label for="delete-rejection-reason" style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.3px;">
                    Reason for Deletion <span style="color:#dc2626;">*</span>
                </label>
                <textarea
                    id="delete-rejection-reason"
                    name="rejection_reason"
                    rows="4"
                    required
                    maxlength="1000"
                    placeholder="Provide a reason for deleting this resident..."
                    style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; font-family:inherit; color:#111827; resize:vertical; outline:none; transition:border-color 0.15s ease;"
                    onfocus="this.style.borderColor='#dc2626'"
                    onblur="this.style.borderColor='#d1d5db'"></textarea>
                <div id="delete-reason-error" style="display:none; font-size:12px; color:#dc2626; margin-top:4px;">
                    Please provide a reason.
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:8px;">
                <button type="button" class="btn btn-secondary" data-close-delete style="padding:9px 16px; border-radius:8px; border:1px solid #d1d5db; background:#fff; color:#374151; font-size:13px; font-weight:600; cursor:pointer;">
                    Cancel
                </button>
                <button type="submit" style="padding:9px 16px; border-radius:8px; border:none; background:#dc2626; color:#fff; font-size:13px; font-weight:600; cursor:pointer;">
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

</x-app-layout>