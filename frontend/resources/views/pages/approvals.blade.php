@vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/css/approvals.css', 'resources/js/app.js', 'resources/js/approvals.js'])
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

            {{-- Tabs --}}
            <div class="approvals-tabs">
                <button class="tab-btn active" data-target="residents-tab">Resident <span class="badge">3</span></button>
                <button class="tab-btn" data-target="pets-tab">Pets <span class="badge">2</span></button>
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
                        <tr>
                            <td>
                                <div class="info-cell">
                                    <div class="resident-avatar">MS</div>
                                    <span class="info-name">Maria Santos</span>
                                </div>
                            </td>
                            <td class="email-col">maria.santos@email.com</td>
                            <td>0917-123-4567</td>
                            <td>Apr 7, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-approve">
                                        <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Approve
                                    </button>
                                    <button class="btn-reject">
                                        <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="info-cell">
                                    <div class="resident-avatar">PC</div>
                                    <span class="info-name">Pedro Cruz</span>
                                </div>
                            </td>
                            <td class="email-col">pedro.cruz@email.com</td>
                            <td>0918-234-5678</td>
                            <td>Apr 6, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-approve">
                                        <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Approve
                                    </button>
                                    <button class="btn-reject">
                                        <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="info-cell">
                                    <div class="resident-avatar">AR</div>
                                    <span class="info-name">Ana Reyes</span>
                                </div>
                            </td>
                            <td class="email-col">ana.reyes@email.com</td>
                            <td>0919-345-6789</td>
                            <td>Apr 5, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-approve">
                                        <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Approve
                                    </button>
                                    <button class="btn-reject">
                                        <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- 2: Pets Pending Approvals --}}
            <div class="approvals-card tab-content" id="pets-tab">
                <div class="approvals-card-header">
                    <h2 class="approvals-card-title" style="color: var(--text-primary);">Pending Pet Registrations <span class="title-badge">2</span></h2>
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
                        <tr>
                            <td>
                                <div class="info-cell">
                                    <div class="pet-avatar"></div>
                                    <span class="info-name">Buddy</span>
                                </div>
                            </td>
                            <td>
                                <span class="info-name">Dog</span>
                                <span class="info-sub" style="color: var(--blue);">Beagle</span>
                            </td>
                            <td>Ana Reyes</td>
                            <td>0919-345-6789</td>
                            <td>Apr 7, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-approve">
                                        <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Approve
                                    </button>
                                    <button class="btn-reject">
                                        <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="info-cell">
                                    <div class="pet-avatar"></div>
                                    <span class="info-name">Charlie</span>
                                </div>
                            </td>
                            <td>
                                <span class="info-name">Cat</span>
                                <span class="info-sub" style="color: var(--blue);">Poodle</span>
                            </td>
                            <td>Rosa Lopez</td>
                            <td>0921-555-6666</td>
                            <td>Apr 6, 2026</td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-approve">
                                        <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Approve
                                    </button>
                                    <button class="btn-reject">
                                        <svg class="btn-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

</x-app-layout>
