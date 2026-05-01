@vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/pets.css', 'resources/css/modals.css', 'resources/js/app.js', 'resources/js/action-modals.js', 'resources/js/pets.js'])
<x-app-layout>

<div class="dashboard-layout">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    <div class="dashboard-main">

        <header class="topbar">
            <h1 class="topbar-title">Pets</h1>
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

        <main class="page-content">

            @if(session('success'))
                <div style="background:#d1fae5; color:#065f46; padding:10px 16px; border-radius:8px; margin-bottom:16px;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Summary Filters --}}
            <div class="pets-summary-filters">
                <button class="summary-btn active" data-filter="all">All <span class="badge">{{ $counts['all'] }}</span></button>
                <button class="summary-btn" data-filter="cat">Cats <span class="badge">{{ $counts['cats'] }}</span></button>
                <button class="summary-btn" data-filter="dog">Dogs <span class="badge">{{ $counts['dogs'] }}</span></button>
                <button class="summary-btn" data-filter="deceased">Deceased <span class="badge">{{ $counts['deceased'] }}</span></button>
            </div>

            <div class="pets-main-filters">
                <div class="pets-search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" id="pets-search" placeholder="Search by pet name, owner, type, or breed..." autocomplete="off">
                </div>
            </div>

            {{-- Pending Deceased Reports --}}
            <div class="pets-card pending-card">
                <div class="pets-card-header">
                    <h2 class="pets-card-title">Pending Deceased Reports <span class="title-badge">{{ $pendingDeceased->count() }}</span></h2>
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
                        @forelse($pendingDeceased as $report)
                            <tr>
                                <td>{{ $report->pet->pet_name ?? 'N/A' }}</td>
                                <td>{{ $report->pet->owner->user_name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($report->date_of_death)->format('M j, Y') }}</td>
                                <td>{{ $report->cause }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="btn-confirm"
                                            data-action-confirm
                                            data-url="{{ route('pets.deceased.confirm', $report->report_id) }}"
                                            data-title="Confirm Deceased Report"
                                            data-message="Confirm that {{ $report->pet->pet_name ?? 'this pet' }} is deceased? This will mark the pet as deceased.">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            Confirm
                                        </button>
                                        <button type="button" class="btn-reject"
                                            data-action-reject
                                            data-url="{{ route('pets.deceased.reject', $report->report_id) }}"
                                            data-title="Reject Deceased Report"
                                            data-message="Please provide a reason for rejecting this report for {{ $report->pet->pet_name ?? 'this pet' }}.">
                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                            Reject
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:40px; color:#9ca3af;">
                                    No pending deceased reports
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- All Pets --}}
            <div class="pets-card">
                <div class="pets-card-header">
                    <h2 class="pets-card-title">All Pets ({{ $pets->count() }})</h2>
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
                        @forelse($pets as $pet)
                            @php
                                $h = $pet->healthRecord;
                                $vac = $h && $h->vaccinated;
                                $dew = $h && $h->dewormed;
                                $spa = $h && $h->spayed_neutered;
                            @endphp
                            <tr
                                style="cursor: pointer;"
                                data-type="{{ strtolower($pet->pet_type) }}"
                                data-status="{{ $pet->status }}"
                                data-name="{{ strtolower($pet->pet_name) }}"
                                data-owner="{{ strtolower($pet->owner->user_name ?? '') }}"
                                data-pet-id="{{ $pet->pet_id }}"
                                data-pet-name="{{ $pet->pet_name }}"
                                data-pet-type="{{ $pet->pet_type }}"
                                data-breed="{{ $pet->breed }}"
                                data-gender="{{ $pet->gender }}"
                                data-age="{{ $pet->age }}"
                                data-color="{{ $pet->color_and_description }}"
                                data-registered="{{ \Carbon\Carbon::parse($pet->registered_at)->format('M j, Y') }}"
                                data-owner-name="{{ $pet->owner->user_name ?? 'N/A' }}"
                                data-owner-contact="{{ $pet->owner->contact_num ?? 'N/A' }}"
                                data-vaccinated="{{ $vac ? '1' : '0' }}"
                                data-vaccinated-date="{{ $vac ? $h->vaccinated_date : '' }}"
                                data-dewormed="{{ $dew ? '1' : '0' }}"
                                data-dewormed-date="{{ $dew ? $h->dewormed_date : '' }}"
                                data-spayed="{{ $spa ? '1' : '0' }}"
                                data-spayed-date="{{ $spa ? $h->spayed_date : '' }}"
                                data-health-notes="{{ $h->description ?? '' }}"
                            >
                                <td>
                                    <div class="pet-name-cell">
                                        @include('partials.pet-icon', ['type' => $pet->pet_type])
                                        <span class="pet-name">{{ $pet->pet_name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="pet-info">
                                        <span class="pet-name">{{ ucfirst($pet->pet_type) }}</span>
                                        <span class="pet-breed">{{ $pet->breed }}</span>
                                    </div>
                                </td>
                                <td>{{ $pet->owner->user_name ?? 'N/A' }}</td>
                                <td><span class="status-badge {{ $vac ? 'yes' : 'no' }}">{{ $vac ? 'Yes' : 'No' }}</span></td>
                                <td><span class="status-badge {{ $dew ? 'yes' : 'no' }}">{{ $dew ? 'Yes' : 'No' }}</span></td>
                                <td><span class="status-badge {{ $spa ? 'yes' : 'no' }}">{{ $spa ? 'Yes' : 'No' }}</span></td>
                                <td><span class="status-badge {{ $pet->status }}">{{ ucfirst($pet->status) }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($pet->registered_at)->format('M j, Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-icon-btn edit" title="Edit Health Record">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <button type="button"
                                                class="action-icon-btn delete"
                                                title="Delete"
                                                data-delete-pet-btn
                                                data-name="{{ $pet->pet_name }}"
                                                data-action="{{ route('pets.destroy', $pet->pet_id) }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align:center; padding:40px; color:#9ca3af;">
                                    No pets found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

{{-- View Pet Modal --}}
<div class="modal-overlay" id="pet-view-modal">
    <div class="modal-container" style="max-width: 600px;">
        <div class="modal-header">
            <h3 class="modal-title">Pet Details</h3>
            <button class="modal-close" data-close-view>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div style="display:flex; align-items:center; gap:14px; margin-bottom:20px;">
            <div id="modal-pet-avatar" class="pet-avatar pet-avatar-lg"></div>
            <div>
                <div id="modal-pet-name" style="font-size:18px; font-weight:700; color:#111827;"></div>
                <span id="modal-pet-status" class="status-badge"></span>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px 24px; margin-bottom:20px;">
            <div><div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Type</div><div id="modal-pet-type" style="font-size:14px; color:#374151;"></div></div>
            <div><div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Breed</div><div id="modal-pet-breed" style="font-size:14px; color:#374151;"></div></div>
            <div><div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Gender</div><div id="modal-pet-gender" style="font-size:14px; color:#374151;"></div></div>
            <div><div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Age</div><div id="modal-pet-age" style="font-size:14px; color:#374151;"></div></div>
            <div><div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Color and Description</div><div id="modal-pet-color" style="font-size:14px; color:#374151;"></div></div>
            <div><div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Registered</div><div id="modal-pet-registered" style="font-size:14px; color:#374151;"></div></div>
        </div>

        <div style="border-top:1px solid #e5e7eb; padding-top:16px; margin-bottom:16px;">
            <h4 style="font-size:14px; font-weight:700; color:#111827; margin-bottom:10px;">Owner Information</h4>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div><div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Name</div><div id="modal-owner-name" style="font-size:14px; color:#374151;"></div></div>
                <div><div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Contact</div><div id="modal-owner-contact" style="font-size:14px; color:#374151;"></div></div>
            </div>
        </div>

        <div style="border-top:1px solid #e5e7eb; padding-top:16px;">
            <h4 style="font-size:14px; font-weight:700; color:#111827; margin-bottom:10px;">Health Record</h4>
            <div style="display:flex; gap:16px; flex-wrap:wrap; margin-bottom:10px;">
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <span id="modal-vaccinated" class="status-badge"></span>
                    <span id="modal-vaccinated-date" style="font-size:11px; color:#6b7280;"></span>
                </div>
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <span id="modal-dewormed" class="status-badge"></span>
                    <span id="modal-dewormed-date" style="font-size:11px; color:#6b7280;"></span>
                </div>
                <div style="display:flex; flex-direction:column; gap:4px;">
                    <span id="modal-spayed" class="status-badge"></span>
                    <span id="modal-spayed-date" style="font-size:11px; color:#6b7280;"></span>
                </div>
            </div>
            <div id="modal-health-notes-wrap" style="display:none;">
                <div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Notes</div>
                <div id="modal-health-notes" style="font-size:13px; color:#374151; background:#f9fafb; padding:10px; border-radius:6px;"></div>
            </div>
        </div>

        <div class="modal-actions" style="margin-top:20px;">
            <button type="button" class="modal-btn modal-btn-primary modal-btn-full" id="btn-edit-health-record">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Health Record
            </button>
        </div>
    </div>
</div>

{{-- Edit Health Record Modal --}}
<div class="modal-overlay" id="pet-edit-health-modal">
    <div class="modal-container" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title">Edit Health Record</h3>
            <button class="modal-close" data-close-edit-health>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        
        <form method="POST" id="edit-health-form">
            @csrf
            @method('PATCH')
            
            <div style="margin-bottom:15px; display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="hidden" name="vaccinated" value="0">
                    <input type="checkbox" name="vaccinated" id="input-vaccinated" value="1" style="width:16px; height:16px;" onchange="document.getElementById('input-vaccinated-date').disabled = !this.checked">
                    <span style="font-size:14px; color:#374151;">Vaccinated</span>
                </label>
                <input type="date" name="vaccinated_date" id="input-vaccinated-date" class="form-input" style="flex:1; max-width:200px; padding:6px 10px; border:1px solid #d1d5db; border-radius:6px; font-size:13px;" disabled>
            </div>
            <div style="margin-bottom:15px; display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="hidden" name="dewormed" value="0">
                    <input type="checkbox" name="dewormed" id="input-dewormed" value="1" style="width:16px; height:16px;" onchange="document.getElementById('input-dewormed-date').disabled = !this.checked">
                    <span style="font-size:14px; color:#374151;">Dewormed</span>
                </label>
                <input type="date" name="dewormed_date" id="input-dewormed-date" class="form-input" style="flex:1; max-width:200px; padding:6px 10px; border:1px solid #d1d5db; border-radius:6px; font-size:13px;" disabled>
            </div>
            <div style="margin-bottom:20px; display:flex; align-items:center; justify-content:space-between; gap:12px;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="hidden" name="spayed_neutered" value="0">
                    <input type="checkbox" name="spayed_neutered" id="input-spayed" value="1" style="width:16px; height:16px;" onchange="document.getElementById('input-spayed-date').disabled = !this.checked">
                    <span style="font-size:14px; color:#374151;">Spayed/Neutered</span>
                </label>
                <input type="date" name="spayed_date" id="input-spayed-date" class="form-input" style="flex:1; max-width:200px; padding:6px 10px; border:1px solid #d1d5db; border-radius:6px; font-size:13px;" disabled>
            </div>
            
            <div style="margin-bottom:20px;">
                <label for="input-health-notes" style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; text-transform:uppercase;">
                    Health Notes
                </label>
                <textarea
                    id="input-health-notes"
                    name="description"
                    rows="4"
                    placeholder="Add any health notes or details here..."
                    style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; font-family:inherit; color:#111827; resize:vertical; outline:none;"></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="modal-btn modal-btn-secondary" data-close-edit-health>
                    Cancel
                </button>
                <button type="submit" class="modal-btn modal-btn-primary">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials.action-modals')

{{-- ─── Delete Pet Confirmation Modal ─── --}}
<div class="modal-overlay" id="pet-delete-modal">
    <div class="modal-container" style="max-width: 420px;">
        <div class="modal-header">
            <h3 class="modal-title">Delete Pet</h3>
            <button class="modal-close" data-close-pet-delete>
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
                    Are you sure you want to delete <strong id="delete-pet-name" style="color:#111827;"></strong>?
                </p>
                <p style="font-size:12.5px; color:#6b7280; margin:0; line-height:1.5;">
                    This action cannot be undone. All data tied to this pet will be permanently removed.
                </p>
            </div>
        </div>

        <form method="POST" id="delete-pet-form">
            @csrf
            @method('DELETE')

            <div style="margin-bottom:18px;">
                <label for="delete-pet-reason" style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.3px;">
                    Reason for Deletion <span style="color:#dc2626;">*</span>
                </label>
                <textarea
                    id="delete-pet-reason"
                    name="rejection_reason"
                    rows="4"
                    required
                    maxlength="1000"
                    placeholder="Provide a reason for deleting this pet..."
                    style="width:100%; padding:10px 12px; border:1px solid #d1d5db; border-radius:8px; font-size:13px; font-family:inherit; color:#111827; resize:vertical; outline:none; transition:border-color 0.15s ease;"
                    onfocus="this.style.borderColor='#dc2626'"
                    onblur="this.style.borderColor='#d1d5db'"></textarea>
                <div id="delete-pet-reason-error" style="display:none; font-size:12px; color:#dc2626; margin-top:4px;">
                    Please provide a reason.
                </div>
            </div>

            <div class="modal-actions">
                <button type="button" class="modal-btn modal-btn-secondary" data-close-pet-delete>
                    Cancel
                </button>
                <button type="submit" class="modal-btn modal-btn-danger">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    Delete
                </button>
            </div>
        </form>
    </div>
</div>

</x-app-layout>