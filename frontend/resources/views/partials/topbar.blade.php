@props(['title' => ''])

<header class="topbar">
    <h1 class="topbar-title">{{ $title }}</h1>
    <div class="topbar-right">
        <div class="notif-wrapper">
            <button class="topbar-icon-btn" id="notif-bell" title="Notifications">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <span class="topbar-notif-dot" id="notif-dot" style="display:none;"></span>
                <span class="topbar-notif-count" id="notif-count" style="display:none;">0</span>
            </button>

            <div class="notif-dropdown" id="notif-dropdown">
                <div class="notif-header">
                    <span>Notifications</span>
                </div>
                <div class="notif-list" id="notif-list">
                    <div class="notif-empty">No new notifications</div>
                </div>
            </div>
        </div>
    </div>
</header>

<audio id="notif-sound" preload="auto">
    <source src="https://cdn.jsdelivr.net/gh/naptha/tessdata@gh-pages/notify.mp3" type="audio/mpeg">
</audio>