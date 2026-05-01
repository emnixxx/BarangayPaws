@vite(['resources/css/sidebar.css', 'resources/css/profile-panel.css', 'resources/js/sidebar.js'])

<aside class="sidebar">

    {{-- Logo --}}
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <img src="{{ asset('img/logo.png') }}"
                 alt="BarangayPaws"
                 onerror="this.onerror=null; this.src='{{ asset('img/logo.svg') }}';">
        </div>
        <div class="sidebar-logo-text">
            <span class="sidebar-logo-name">BarangayPaws</span>
            <span class="sidebar-logo-sub">Admin Panel</span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">

        <a href="{{ route('dashboard') }}" class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            <span class="sidebar-nav-label">Dashboard</span>
        </a>

        <a href="{{ route('residents') }}" class="sidebar-nav-item {{ request()->routeIs('residents.*') ? 'active' : '' }}">
            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span class="sidebar-nav-label">Residents</span>
        </a>

        <a href="{{ route('pets') }}" class="sidebar-nav-item {{ request()->routeIs('pets.*') ? 'active' : '' }}">
            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="7" r="2"/><circle cx="15" cy="7" r="2"/>
                <circle cx="6" cy="13" r="2"/><circle cx="18" cy="13" r="2"/>
                <ellipse cx="12" cy="17" rx="3" ry="2.5"/>
            </svg>
            <span class="sidebar-nav-label">Pets</span>
        </a>

        <a href="{{ route('approvals') }}" class="sidebar-nav-item {{ request()->routeIs('approvals.*') ? 'active' : '' }}">
            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
                <polyline points="10 9 9 9 8 9"/>
            </svg>
            <span class="sidebar-nav-label">Approvals</span>
            <span class="sidebar-badge">23</span>
        </a>

        <a href="{{ route('announcements') }}" class="sidebar-nav-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 17H2a3 3 0 0 0 3-3V9a7 7 0 0 1 14 0v5a3 3 0 0 0 3 3zm-8.27 4a2 2 0 0 1-3.46 0"/>
            </svg>
            <span class="sidebar-nav-label">Announcements</span>
        </a>

        <div class="sidebar-section-label">System</div>

        <a href="{{ route('auditlog') }}" class="sidebar-nav-item {{ request()->routeIs('auditlog*') ? 'active' : '' }}">
            <svg class="sidebar-nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
            </svg>
            <span class="sidebar-nav-label">Audit Logs</span>
        </a>


    </nav>

    {{-- Footer / User info (clickable → opens profile panel) --}}
    <div class="sidebar-footer">
        <button type="button" class="sidebar-footer-main" id="open-profile-panel" title="Open profile">
            <div class="sidebar-footer-avatar">
                {{ strtoupper(substr(auth()->user()->user_name ?? auth()->user()->name ?? 'JD', 0, 2)) }}
            </div>
            <div class="sidebar-footer-info">
                <div class="sidebar-footer-name">{{ auth()->user()->user_name ?? auth()->user()->name ?? 'Juan Dela Cruz' }}</div>
                <div class="sidebar-footer-role">{{ ucfirst(auth()->user()->role ?? 'Admin') }}</div>
            </div>
        </button>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sidebar-logout-btn" title="Log out">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </button>
        </form>
    </div>

</aside>

@include('partials.profile-panel')