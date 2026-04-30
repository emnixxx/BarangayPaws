@vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/pets.css', 'resources/css/notifications.css', 'resources/js/app.js', 'resources/js/pets.js', 'resources/js/notifications.js'])<x-app-layout>

<div class="dashboard-layout">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    <div class="dashboard-main">

        @include('partials.topbar', ['title' => 'Pets'])

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
                    <input type="text" id="pets-search" placeholder="Search by pet name or owner..." autocomplete="off">
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
                                data-type="{{ $pet->pet_type }}"
                                data-status="{{ $pet->status }}"
                                data-name="{{ strtolower($pet->pet_name) }}"
                                data-owner="{{ strtolower($pet->owner->user_name ?? '') }}"
                                data-pet-id="{{ $pet->pet_id }}"
                                data-pet-name="{{ $pet->pet_name }}"
                                data-pet-type="{{ $pet->pet_type }}"
                                data-breed="{{ $pet->breed }}"
                                data-gender="{{ $pet->gender }}"
                                data-age="{{ $pet->age }}"
                                data-color="{{ $pet->color }}"
                                data-registered="{{ \Carbon\Carbon::parse($pet->registered_at)->format('M j, Y') }}"
                                data-owner-name="{{ $pet->owner->user_name ?? 'N/A' }}"
                                data-owner-contact="{{ $pet->owner->contact_num ?? 'N/A' }}"
                                data-vaccinated="{{ $vac ? '1' : '0' }}"
                                data-dewormed="{{ $dew ? '1' : '0' }}"
                                data-spayed="{{ $spa ? '1' : '0' }}"
                                data-health-notes="{{ $h->description ?? '' }}"
                                data-vaccinated-date="{{ $h->vaccinated_date ?? '' }}"
                                data-dewormed-date="{{ $h->dewormed_date ?? '' }}"
                                data-spayed-date="{{ $h->spayed_date ?? '' }}"
                            >
                                <td>
                                    <div class="pet-name-cell">
                                        <div class="pet-avatar"></div>
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
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        </button>
                                        <button class="action-icon-btn edit-health" title="Edit Health Record">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                        </button>
                                        <form method="POST" action="{{ route('pets.destroy', $pet->pet_id) }}" style="display:inline;" onsubmit="return confirm('Delete {{ $pet->pet_name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-icon-btn delete" title="Delete">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
            <div class="pet-avatar" style="width:54px; height:54px;"></div>
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
            <div><div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Color</div><div id="modal-pet-color" style="font-size:14px; color:#374151;"></div></div>
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
            <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:10px;">
                <span id="modal-vaccinated" class="status-badge"></span>
                <span id="modal-dewormed" class="status-badge"></span>
                <span id="modal-spayed" class="status-badge"></span>
            </div>
            <div id="modal-health-notes-wrap" style="display:none;">
                <div style="font-size:11px; color:#9ca3af; text-transform:uppercase; margin-bottom:2px;">Notes</div>
                <div id="modal-health-notes" style="font-size:13px; color:#374151; background:#f9fafb; padding:10px; border-radius:6px;"></div>
            </div>
        </div>

        <div class="modal-footer" style="margin-top:20px;">
            <button type="button" class="btn-outline btn-full" data-close-view>Close</button>
        </div>
    </div>
</div>

{{-- Edit Health Record Modal --}}
<div class="modal-overlay" id="health-form-modal">
    <div class="modal-container form-modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Health Record</h3>
            <button class="modal-close" data-close-health type="button"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
        </div>

        <form method="POST" action="" id="health-form">
            @csrf
            
            <div style="display:flex; align-items:center; gap: 8px; margin-bottom:12px;">
                <input type="checkbox" id="input-vaccinated" name="vaccinated" value="1">
                <label for="input-vaccinated" style="font-weight:600;">Vaccinated</label>
            </div>
            <div class="form-group">
                <label class="form-label">Date (Optional)</label>
                <input type="date" class="form-input" id="input-vac-date" name="vaccinated_date">
            </div>

            <div style="display:flex; align-items:center; gap: 8px; margin-bottom:12px; margin-top:16px;">
                <input type="checkbox" id="input-dewormed" name="dewormed" value="1">
                <label for="input-dewormed" style="font-weight:600;">Dewormed</label>
            </div>
            <div class="form-group">
                <label class="form-label">Date (Optional)</label>
                <input type="date" class="form-input" id="input-dew-date" name="dewormed_date">
            </div>

            <div style="display:flex; align-items:center; gap: 8px; margin-bottom:12px; margin-top:16px;">
                <input type="checkbox" id="input-spayed" name="spayed_neutered" value="1">
                <label for="input-spayed" style="font-weight:600;">Spayed / Neutered</label>
            </div>
            <div class="form-group">
                <label class="form-label">Date (Optional)</label>
                <input type="date" class="form-input" id="input-spa-date" name="spayed_date">
            </div>

            <div class="form-group" style="margin-top:16px;">
                <label class="form-label">Health Notes / Description</label>
                <textarea class="form-input form-textarea" id="input-health-notes" name="description" rows="3"></textarea>
            </div>

            <div class="modal-footer" style="margin-top:20px;">
                <button type="button" class="btn-outline" data-close-health>Cancel</button>
                <button type="submit" class="btn-submit">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@include('partials.action-modals')

</x-app-layout>