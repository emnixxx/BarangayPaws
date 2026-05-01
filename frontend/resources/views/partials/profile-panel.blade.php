@php
    $user = auth()->user();
    $shouldOpen = session('account_panel') === 'open' || $errors->any();
@endphp

{{-- Backdrop --}}
<div id="profile-panel-backdrop" class="profile-backdrop {{ $shouldOpen ? 'open' : '' }}"></div>

{{-- Panel --}}
<aside id="profile-panel" class="profile-panel {{ $shouldOpen ? 'open' : '' }}" aria-label="Account settings">

    <div class="profile-panel-header">
        <div>
            <div class="profile-panel-title">Account Settings</div>
            <div class="profile-panel-sub">Manage your profile, password, and account</div>
        </div>
        <button type="button" class="profile-panel-close" id="close-profile-panel" title="Close">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>

    {{-- Avatar block --}}
    <div class="profile-hero">
        <div class="profile-hero-avatar">
            {{ strtoupper(substr($user->user_name ?? $user->name ?? 'JD', 0, 2)) }}
        </div>
        <div>
            <div class="profile-hero-name">{{ $user->user_name ?? $user->name ?? 'Admin' }}</div>
            <div class="profile-hero-role">{{ ucfirst($user->role ?? 'admin') }} • {{ $user->email }}</div>
        </div>
    </div>

    {{-- Flash --}}
    @if(session('account_status') === 'profile-updated')
        <div class="profile-alert success">Profile updated successfully.</div>
    @elseif(session('account_status') === 'password-updated')
        <div class="profile-alert success">Password changed successfully.</div>
    @endif

    {{-- Tabs --}}
    <div class="profile-tabs">
        <button type="button" class="profile-tab active" data-tab="profile">Profile</button>
        <button type="button" class="profile-tab" data-tab="password">Password</button>
        <button type="button" class="profile-tab danger" data-tab="danger">Delete</button>
    </div>

    {{-- ── Profile tab ── --}}
    <section class="profile-section active" data-section="profile">
        <form method="POST" action="{{ route('account.profile') }}" class="profile-form">
            @csrf
            @method('PATCH')

            <label class="profile-label">Full Name</label>
            <input type="text" name="user_name" class="profile-input" value="{{ old('user_name', $user->user_name ?? $user->name) }}" required>
            @error('user_name') <div class="profile-error">{{ $message }}</div> @enderror

            <label class="profile-label">Email Address</label>
            <input type="email" name="email" class="profile-input" value="{{ old('email', $user->email) }}" required>
            @error('email') <div class="profile-error">{{ $message }}</div> @enderror

            <button type="submit" class="profile-btn primary">Save Changes</button>
        </form>
    </section>

    {{-- ── Password tab ── --}}
    <section class="profile-section" data-section="password">
        <form method="POST" action="{{ route('account.password') }}" class="profile-form">
            @csrf
            @method('PATCH')

            <label class="profile-label">Current Password</label>
            <input type="password" name="current_password" class="profile-input" autocomplete="current-password" required>
            @error('current_password') <div class="profile-error">{{ $message }}</div> @enderror

            <label class="profile-label">New Password</label>
            <input type="password" name="password" class="profile-input" autocomplete="new-password" minlength="8" required>
            @error('password') <div class="profile-error">{{ $message }}</div> @enderror

            <label class="profile-label">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="profile-input" autocomplete="new-password" minlength="8" required>

            <button type="submit" class="profile-btn primary">Update Password</button>
        </form>
    </section>

    {{-- ── Danger zone ── --}}
    <section class="profile-section" data-section="danger">
        <div class="profile-danger-box">
            <div class="profile-danger-title">Delete Account</div>
            <p class="profile-danger-desc">
                Once your account is deleted, all of its resources and data will be permanently removed.
                This action cannot be undone — please be certain before proceeding.
            </p>
            <form method="POST" action="{{ route('account.destroy') }}" class="profile-form" id="delete-account-form">
                @csrf
                @method('DELETE')

                <label class="profile-label">Confirm Password</label>
                <input type="password" name="password" class="profile-input" autocomplete="current-password" placeholder="Enter your password" required>
                @error('password') <div class="profile-error">{{ $message }}</div> @enderror

                <button type="submit" class="profile-btn danger">Delete My Account</button>
            </form>
        </div>
    </section>

</aside>

<script>
(function () {
    const backdrop = document.getElementById('profile-panel-backdrop');
    const panel    = document.getElementById('profile-panel');
    const opener   = document.getElementById('open-profile-panel');
    const closer   = document.getElementById('close-profile-panel');

    if (!backdrop || !panel) return;

    const openPanel  = () => { panel.classList.add('open'); backdrop.classList.add('open'); };
    const closePanel = () => { panel.classList.remove('open'); backdrop.classList.remove('open'); };

    if (opener) opener.addEventListener('click', openPanel);
    if (closer) closer.addEventListener('click', closePanel);
    backdrop.addEventListener('click', closePanel);
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closePanel(); });

    // Tab switching
    panel.querySelectorAll('.profile-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            panel.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
            panel.querySelectorAll('.profile-section').forEach(s => s.classList.remove('active'));
            tab.classList.add('active');
            const target = panel.querySelector(`[data-section="${tab.dataset.tab}"]`);
            if (target) target.classList.add('active');
        });
    });

    // Confirm before deleting
    const delForm = document.getElementById('delete-account-form');
    if (delForm) {
        delForm.addEventListener('submit', (e) => {
            if (!confirm('Are you absolutely sure you want to delete your account? This cannot be undone.')) {
                e.preventDefault();
            }
        });
    }
})();
</script>
