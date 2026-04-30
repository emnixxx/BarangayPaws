@vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/approvals.css', 'resources/css/notifications.css', 'resources/js/app.js', 'resources/js/approvals.js', 'resources/js/notifications.js'])<x-app-layout>

<div class="dashboard-layout">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content --}}
    <div class="dashboard-main">

 @include('partials.topbar', ['title' => 'Approvals'])

        {{-- Page Content --}}
        <main class="page-content">

            {{-- Success Flash --}}
            @if(session('success'))
                <div style="background:#d1fae5; color:#065f46; padding:10px 16px; border-radius:8px; margin-bottom:16px;">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tabs --}}
            <div class="approvals-tabs">
                <button class="tab-btn active" data-target="residents-tab">
                    Resident <span class="badge">{{ $pendingResidents->count() }}</span>
                </button>
                <button class="tab-btn" data-target="pets-tab">
                    Pets <span class="badge">{{ $pendingPets->count() }}</span>
                </button>
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
                                        <button type="button" class="btn-view"
                                            data-id="{{ $resident->user_id }}"
                                            data-name="{{ $resident->user_name }}"
                                            data-email="{{ $resident->email }}"
                                            data-contact="{{ $resident->contact_num }}"
                                            data-gender="{{ $resident->gender }}"
                                            data-address="{{ $resident->address }}"
                                            data-registered="{{ \Carbon\Carbon::parse($resident->date_registered)->format('M j, Y g:i A') }}">
                                            <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            View
                                        </button>
                                        <form method="POST" action="{{ route('approvals.resident.approve', $resident->user_id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn-approve">
                                                <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('approvals.resident.reject', $resident->user_id) }}" style="display:inline;" onsubmit="return confirm('Reject this resident?');">
                                            @csrf
                                            <button type="submit" class="btn-reject">
                                                <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                Reject
                                            </button>
                                        </form>
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
                                        <div class="pet-avatar"></div>
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
                                        <form method="POST" action="{{ route('approvals.pet.approve', $pet->pet_id) }}" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn-approve">
                                                <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('approvals.pet.reject', $pet->pet_id) }}" style="display:inline;" onsubmit="return confirm('Reject this pet?');">
                                            @csrf
                                            <button type="submit" class="btn-reject">
                                                <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                Reject
                                            </button>
                                        </form>
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

            {{-- Resident View Modal --}}
            <div class="modal-overlay" id="residentViewModal">
                <div class="modal-box">
                    <div class="modal-header">
                        <h3 class="modal-title">Resident Details</h3>
                        <button type="button" class="modal-close" id="closeResidentModal">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-avatar-wrap">
                            <div class="modal-avatar" id="modalAvatar"></div>
                            <div>
                                <div class="modal-name" id="modalName"></div>
                                <div class="modal-sub" id="modalEmail"></div>
                            </div>
                        </div>

                        <div class="modal-grid">
                            <div class="modal-field">
                                <label>Contact Number</label>
                                <span id="modalContact"></span>
                            </div>
                            <div class="modal-field">
                                <label>Gender</label>
                                <span id="modalGender"></span>
                            </div>
                            <div class="modal-field modal-field-full">
                                <label>Address</label>
                                <span id="modalAddress"></span>
                            </div>
                            <div class="modal-field modal-field-full">
                                <label>Date Registered</label>
                                <span id="modalRegistered"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

</x-app-layout>