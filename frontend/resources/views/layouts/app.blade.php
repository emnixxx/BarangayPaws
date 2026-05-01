<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        @if(auth()->check() && auth()->user()->role === 'admin')
            <!-- Global Admin Notifications -->
            <audio id="notification-sound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

            {{-- Notification dropdown panel (single instance, attached to whichever bell is clicked) --}}
            <div id="notif-panel" class="notif-panel" hidden>
                <div class="notif-panel-header">
                    <div>
                        <div class="notif-panel-title">Notifications</div>
                        <div class="notif-panel-sub" id="notif-panel-sub">No pending items</div>
                    </div>
                    <a href="{{ route('approvals') }}" class="notif-panel-link">View all</a>
                </div>
                <div class="notif-panel-body" id="notif-panel-body">
                    <div class="notif-empty">No pending approvals</div>
                </div>
            </div>

            <style>
                .topbar-icon-btn { position: relative; }
                .topbar-notif-count {
                    position: absolute;
                    top: -2px; right: -2px;
                    min-width: 18px; height: 18px;
                    padding: 0 5px;
                    background: #dc2626;
                    color: #fff;
                    font-size: 10px;
                    font-weight: 700;
                    border-radius: 9px;
                    display: none;
                    align-items: center;
                    justify-content: center;
                    line-height: 1;
                    box-shadow: 0 0 0 2px var(--surface, #fff);
                }
                .topbar-notif-count.has-count { display: inline-flex; }
                .topbar-notif-count.pulse { animation: notif-pulse 0.6s ease; }
                @keyframes notif-pulse {
                    0%   { transform: scale(1); }
                    50%  { transform: scale(1.35); }
                    100% { transform: scale(1); }
                }

                .notif-panel[hidden] { display: none !important; }
                .notif-panel {
                    position: fixed;
                    width: 360px;
                    max-height: 480px;
                    background: #fff;
                    border-radius: 12px;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.14), 0 2px 6px rgba(0,0,0,0.06);
                    border: 1px solid #eef0ef;
                    z-index: 2000;
                    overflow: hidden;
                    display: flex;
                    flex-direction: column;
                    opacity: 0;
                    transform: translateY(-6px);
                    transition: opacity 0.15s ease, transform 0.15s ease;
                }
                .notif-panel.open { opacity: 1; transform: translateY(0); }

                .notif-panel-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    padding: 16px 18px 12px;
                    border-bottom: 1px solid #f1f3f2;
                }
                .notif-panel-title { font-size: 15px; font-weight: 700; color: #111827; }
                .notif-panel-sub { font-size: 12px; color: #6b7280; margin-top: 2px; }
                .notif-panel-link {
                    font-size: 12px; font-weight: 600;
                    color: #2d8a5e; text-decoration: none;
                    padding: 4px 8px; border-radius: 6px;
                    transition: background 0.15s ease;
                }
                .notif-panel-link:hover { background: #e3f3ea; }

                .notif-panel-body { overflow-y: auto; flex: 1; padding: 6px 0; }
                .notif-empty {
                    text-align: center;
                    padding: 36px 16px;
                    color: #9ca3af;
                    font-size: 13px;
                }

                .notif-item {
                    display: flex;
                    gap: 12px;
                    align-items: flex-start;
                    padding: 12px 18px;
                    cursor: pointer;
                    border-bottom: 1px solid #f5f7f6;
                    transition: background 0.12s ease;
                }
                .notif-item:last-child { border-bottom: none; }
                .notif-item:hover { background: #f7faf8; }

                .notif-icon {
                    width: 36px; height: 36px;
                    border-radius: 50%;
                    display: flex; align-items: center; justify-content: center;
                    flex-shrink: 0;
                }
                .notif-icon.resident { background: #e3eef9; color: #2563eb; }
                .notif-icon.pet      { background: #fff1e3; color: #d97706; }

                .notif-text { flex: 1; min-width: 0; }
                .notif-title-row {
                    display: flex;
                    justify-content: space-between;
                    gap: 8px;
                    align-items: baseline;
                }
                .notif-title {
                    font-size: 13.5px;
                    font-weight: 600;
                    color: #111827;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
                .notif-time { font-size: 11px; color: #9ca3af; flex-shrink: 0; font-weight: 500; }
                .notif-sub {
                    font-size: 12px; color: #6b7280;
                    margin-top: 2px;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                .notif-section-label {
                    font-size: 10.5px;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    font-weight: 700;
                    color: #9ca3af;
                    padding: 10px 18px 6px;
                }
            </style>

            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    let previousCount = null;
                    const sound       = document.getElementById('notification-sound');
                    const panel       = document.getElementById('notif-panel');
                    const panelBody   = document.getElementById('notif-panel-body');
                    const panelSub    = document.getElementById('notif-panel-sub');
                    const COUNT_URL   = '{{ route('notifications.count') }}';
                    const ITEMS_URL   = '{{ route('notifications.items') }}';
                    const APPROVALS   = '{{ route('approvals') }}';

                    // ── Inject count badges into every bell button ──
                    document.querySelectorAll('.topbar-icon-btn').forEach(btn => {
                        if (!btn.querySelector('.topbar-notif-count')) {
                            const span = document.createElement('span');
                            span.className = 'topbar-notif-count';
                            btn.appendChild(span);
                        }
                        // hide the old static dot — we'll use the count badge instead
                        btn.querySelectorAll('.topbar-notif-dot').forEach(d => d.style.display = 'none');
                    });

                    // ── Count poller ──
                    const updateCount = () => {
                        fetch(COUNT_URL, { headers: { 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' }})
                            .then(r => r.json())
                            .then(data => {
                                const c = data.count;

                                document.querySelectorAll('.sidebar-badge').forEach(b => {
                                    b.textContent = c > 0 ? c : '';
                                    b.style.display = c > 0 ? 'flex' : 'none';
                                });

                                document.querySelectorAll('.topbar-notif-count').forEach(b => {
                                    b.textContent = c > 9 ? '9+' : c;
                                    b.classList.toggle('has-count', c > 0);
                                    if (previousCount !== null && c > previousCount) {
                                        b.classList.remove('pulse');
                                        void b.offsetWidth; // restart animation
                                        b.classList.add('pulse');
                                    }
                                });

                                if (previousCount !== null && c > previousCount) {
                                    sound && sound.play().catch(()=>{});
                                    if (panel && !panel.hidden) loadItems();
                                }
                                previousCount = c;
                            })
                            .catch(err => console.error('Notif count failed:', err));
                    };

                    // ── Items loader ──
                    const renderItems = (data) => {
                        if (!data.items || data.items.length === 0) {
                            panelBody.innerHTML = `<div class="notif-empty">No pending approvals</div>`;
                            panelSub.textContent = 'No pending items';
                            return;
                        }
                        panelSub.textContent = `${data.residents_count} resident(s), ${data.pets_count} pet(s) pending`;

                        const groups = { resident: [], pet: [] };
                        data.items.forEach(it => groups[it.type].push(it));

                        const iconSvg = {
                            resident: `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>`,
                            pet:      `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="7" r="2"/><circle cx="15" cy="7" r="2"/><circle cx="6" cy="13" r="2"/><circle cx="18" cy="13" r="2"/><ellipse cx="12" cy="17" rx="3" ry="2.5"/></svg>`,
                        };

                        const sectionHtml = (label, items, type) => {
                            if (!items.length) return '';
                            return `<div class="notif-section-label">${label}</div>` + items.map(it => `
                                <div class="notif-item" data-href="${APPROVALS}">
                                    <div class="notif-icon ${type}">${iconSvg[type]}</div>
                                    <div class="notif-text">
                                        <div class="notif-title-row">
                                            <div class="notif-title">${escapeHtml(it.title)}</div>
                                            <div class="notif-time">${escapeHtml(it.time)}</div>
                                        </div>
                                        <div class="notif-sub">${escapeHtml(it.sub)}</div>
                                    </div>
                                </div>
                            `).join('');
                        };

                        panelBody.innerHTML =
                            sectionHtml('Pending Residents', groups.resident, 'resident') +
                            sectionHtml('Pending Pets',      groups.pet,      'pet');

                        panelBody.querySelectorAll('.notif-item').forEach(el => {
                            el.addEventListener('click', () => window.location.href = el.dataset.href);
                        });
                    };

                    const loadItems = () => {
                        panelBody.innerHTML = `<div class="notif-empty">Loading…</div>`;
                        fetch(ITEMS_URL, { headers: { 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest' }})
                            .then(r => r.json())
                            .then(renderItems)
                            .catch(() => panelBody.innerHTML = `<div class="notif-empty">Failed to load</div>`);
                    };

                    // ── Bell click toggles panel ──
                    const positionPanel = (anchor) => {
                        const r = anchor.getBoundingClientRect();
                        const panelW = 360;
                        let right = window.innerWidth - r.right;
                        if (right < 8) right = 8;
                        panel.style.right = right + 'px';
                        panel.style.top = (r.bottom + 8) + 'px';
                        panel.style.left = 'auto';
                    };

                    document.addEventListener('click', (e) => {
                        const btn = e.target.closest('.topbar-icon-btn');
                        if (!btn) return;
                        e.preventDefault();
                        e.stopPropagation();
                        if (!panel.hidden) {
                            panel.classList.remove('open');
                            panel.hidden = true;
                            return;
                        }
                        positionPanel(btn);
                        panel.hidden = false;
                        requestAnimationFrame(() => panel.classList.add('open'));
                        loadItems();
                    });

                    // Click outside closes
                    document.addEventListener('click', (e) => {
                        if (panel.hidden) return;
                        if (panel.contains(e.target)) return;
                        if (e.target.closest('.topbar-icon-btn')) return;
                        panel.classList.remove('open');
                        panel.hidden = true;
                    });

                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape' && !panel.hidden) {
                            panel.classList.remove('open');
                            panel.hidden = true;
                        }
                    });

                    function escapeHtml(s) {
                        return String(s ?? '').replace(/[&<>"']/g, ch =>
                            ({ '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' }[ch])
                        );
                    }

                    updateCount();
                    setInterval(updateCount, 5000);
                });
            </script>
        @endif
    </body>
</html>
