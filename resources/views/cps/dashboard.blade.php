<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CPS Authentication Dashboard — Poltekad Kodiklatad</title>

    <!-- Google Fonts: Poppins & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700;800&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS (Vite / Standalone Tokens) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Poppins"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    boxShadow: {
                        '2xs': '0 1px 2px 0 rgba(0, 0, 0, 0.04)',
                        'xs': '0 1px 3px 0 rgba(0, 0, 0, 0.06), 0 1px 2px -1px rgba(0, 0, 0, 0.06)',
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full font-sans text-slate-800 bg-slate-100 antialiased selection:bg-indigo-100 selection:text-indigo-900">

    <!-- 1. LEFT SIDEBAR NAVIGATION (Enterprise Poltekad Standard) -->
    @include('cps.partials.sidebar')

    <!-- 2. MAIN APP WRAPPER (Offset by sidebar width on lg screens) -->
    <div class="lg:pl-64 flex flex-col min-h-full">
        
        <!-- TOPBAR HEADER -->
        @include('cps.partials.topbar')

        <!-- MAIN CONTENT WORKSPACE -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto pb-24 lg:pb-8">
            
            <!-- Tab 1: Live Authentication (Utama) -->
            @include('cps.partials.tab-live')

            <!-- Tab 2: Verification Logs History -->
            @include('cps.partials.tab-history')

            <!-- Tab 3: Usability Task Completion Time (TCT) Lab -->
            @include('cps.partials.tab-usability-tct')

            <!-- Tab 4: SUS Questionnaire Form -->
            @include('cps.partials.tab-sus-form')

            <!-- Tab 5: Usability Research Analytics & SUS Stats -->
            @include('cps.partials.tab-analytics')

            <!-- Tab 6: Edge Gate & Sensor Nodes -->
            @include('cps.partials.tab-devices')

        </main>

    </div>

    <!-- 3. THUMB ZONE BOTTOM ACTION BAR (Mobile & Tablet) -->
    @include('cps.partials.thumb-bar')

    <!-- TOAST NOTIFICATION CONTAINER -->
    <div id="toast-container" class="fixed top-20 right-4 z-50 space-y-2 pointer-events-none"></div>

    <!-- MAIN CLIENT CONTROL SCRIPT -->
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        let liveEventSource = null;
        let activeTab = 'live';
        let latestLogId = {{ $latestLog->id ?? 0 }};

        // Usability Task Testing State
        let isTaskRunning = false;
        let taskStartTime = null;
        let taskTimerInterval = null;
        let activeTaskSessionId = null;
        let taskMisclicks = 0;
        let taskClicks = 0;

        // View Titles Mapping
        const viewMeta = {
            live: {
                title: 'PUSAT AUTENTIKASI REAL-TIME',
                badge: 'HCD LIVE VIEW',
                desc: 'Pemantauan telemetri wajah biometrik gerbang Poltekad secara real-time sub-100ms'
            },
            history: {
                title: 'RIWAYAT & LOG AUTENTIKASI',
                badge: 'DATABASE LOGS',
                desc: 'Rekapitulasi seluruh riwayat akses gerbang, pencarian personel, dan filter anomali'
            },
            tct: {
                title: 'PENGUJIAN USABILITY (TCT LAB)',
                badge: 'HCD EVALUATOR',
                desc: 'Pengukuran waktu penyelesaian tugas (Task Completion Time) dan tingkat kesalahan operator'
            },
            sus: {
                title: 'KUESIONER SYSTEM USABILITY SCALE',
                badge: 'STANDAR SUS',
                desc: 'Evaluasi 10 butir pertanyaan skala Likert 1-5 dengan kalkulator skor live target > 75'
            },
            analytics: {
                title: 'ANALISIS HASIL RISET & STATISTIK',
                badge: 'KUANTITATIF',
                desc: 'Rekapitulasi deskriptif skor SUS, rata-rata durasi TCT, dan latensi WebSocket untuk skripsi/riset'
            },
            devices: {
                title: 'STATUS NODE GERBANG & SENSOR',
                badge: 'HARDWARE EDGE',
                desc: 'Kamera pengawas edge CPS, latensi transmisi, dan status operasional gerbang'
            }
        };

        // Initialize on DOM Ready
        window.addEventListener('DOMContentLoaded', () => {
            initServerClock();
            initRealtimeSSE();
            initMisclickTracker();
            fetchUsabilityStats();
        });

        // Tab Switching Logic
        function switchTab(tabKey) {
            if (!viewMeta[tabKey]) return;
            activeTab = tabKey;

            const tabs = ['live', 'history', 'tct', 'sus', 'analytics', 'devices'];
            
            tabs.forEach(t => {
                const el = document.getElementById(`tab-content-${t}`);
                const nav = document.getElementById(`nav-item-${t}`);
                
                if (t === tabKey) {
                    if (el) el.classList.remove('hidden');
                    if (nav) {
                        nav.className = "w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-medium";
                    }
                } else {
                    if (el) el.classList.add('hidden');
                    if (nav) {
                        nav.className = "w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all font-medium";
                    }
                }
            });

            // Update Topbar Title & Meta
            const titleEl = document.getElementById('topbar-view-title');
            const badgeEl = document.getElementById('topbar-view-badge');
            const descEl = document.getElementById('topbar-view-desc');

            if (titleEl) titleEl.innerText = viewMeta[tabKey].title;
            if (badgeEl) badgeEl.innerText = viewMeta[tabKey].badge;
            if (descEl) descEl.innerText = viewMeta[tabKey].desc;

            // Close mobile sidebar if open
            const sidebar = document.getElementById('cps-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar && !sidebar.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
                toggleSidebarMobile();
            }

            if (tabKey === 'analytics') {
                fetchUsabilityStats();
            }
        }

        // Mobile Sidebar Toggle
        function toggleSidebarMobile() {
            const sidebar = document.getElementById('cps-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (!sidebar) return;

            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                if (backdrop) backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                if (backdrop) backdrop.classList.add('hidden');
            }
        }

        // Server Clock
        function initServerClock() {
            const clockEl = document.getElementById('live-server-clock');
            setInterval(() => {
                const now = new Date();
                if (clockEl) clockEl.innerText = now.toTimeString().split(' ')[0];
            }, 1000);
        }

        // Real-Time SSE Stream (Sub-100ms)
        function initRealtimeSSE() {
            if (typeof window.EventSource !== 'undefined') {
                try {
                    if (liveEventSource) liveEventSource.close();
                    liveEventSource = new EventSource('/api/verifications/stream');

                    liveEventSource.addEventListener('connected', () => setConnectionStatus(true));
                    liveEventSource.addEventListener('face_verified', (e) => {
                        const log = JSON.parse(e.data);
                        handleIncomingVerification(log);
                    });
                    liveEventSource.onerror = () => setConnectionStatus(false);
                } catch (err) {
                    setConnectionStatus(false);
                }
            } else {
                setInterval(pollVerificationStats, 2000);
            }
        }

        function setConnectionStatus(online) {
            const wsPulse = document.getElementById('ws-pulse');
            const wsDot = document.getElementById('ws-dot');
            const wsText = document.getElementById('ws-status-text');

            if (online) {
                if (wsPulse) wsPulse.className = "animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75";
                if (wsDot) wsDot.className = "relative inline-flex rounded-full h-2 w-2 bg-emerald-500";
                if (wsText) wsText.innerText = "WEBSOCKET LIVE";
            } else {
                if (wsPulse) wsPulse.className = "hidden";
                if (wsDot) wsDot.className = "relative inline-flex rounded-full h-2 w-2 bg-amber-500";
                if (wsText) wsText.innerText = "RECONNECTING";
            }
        }

        // Process Incoming Log (<100ms Update)
        function handleIncomingVerification(log) {
            latestLogId = log.id;

            // 1. Update Focal Card
            updateHeroCard(log);

            // 2. Prepend Table & Live Timeline
            prependLogRow(log);
            prependLiveStreamTimeline(log);

            // 3. Update KPI Counters
            pollVerificationStats();

            // 4. Play Sound
            playVerificationTone(log.status);

            if (isTaskRunning && log.status === 'failed') {
                showToast(`Log Gagal Terdeteksi: ${log.subject_name}`, 'warning');
            }
        }

        function updateHeroCard(log) {
            const photoEl = document.getElementById('hero-photo');
            const nameEl = document.getElementById('hero-subject-name');
            const nimEl = document.getElementById('hero-subject-nim');
            const locEl = document.getElementById('hero-location');
            const devEl = document.getElementById('hero-device-id');
            const confVal = document.getElementById('hero-confidence-val');
            const confBar = document.getElementById('hero-confidence-bar');
            const latVal = document.getElementById('hero-latency-text');
            const headerLat = document.getElementById('header-latency-val');
            const badgeContainer = document.getElementById('hero-status-badge-container');
            const catBadge = document.getElementById('hero-category-badge');
            const failRibbon = document.getElementById('hero-failed-ribbon');
            const failNoteContainer = document.getElementById('hero-failure-note-container');
            const failNote = document.getElementById('hero-failure-note');

            if (photoEl && log.photo_url) photoEl.src = log.photo_url;
            if (nameEl) nameEl.innerText = log.subject_name;
            if (nimEl) nimEl.innerText = log.nim || 'N/A';
            if (locEl) locEl.innerText = log.location || 'Gate Utama Poltekad';
            if (devEl) devEl.innerText = `[${log.device_id}]`;
            if (catBadge) catBadge.innerText = log.category || 'Taruna';

            if (confVal) confVal.innerText = `${log.confidence_score}%`;
            if (confBar) {
                confBar.style.width = `${Math.min(100, log.confidence_score)}%`;
                confBar.className = log.status === 'failed' ? 'h-full rounded-full bg-rose-500 transition-all duration-500' : 'h-full rounded-full bg-emerald-500 transition-all duration-500';
            }

            if (latVal) latVal.innerText = `${log.latency_ms} ms`;
            if (headerLat) headerLat.innerText = `${log.latency_ms} ms`;

            if (failRibbon) {
                if (log.status === 'failed') failRibbon.classList.remove('hidden');
                else failRibbon.classList.add('hidden');
            }

            if (failNoteContainer && failNote) {
                if (log.failure_reason) {
                    failNote.innerText = log.failure_reason;
                    failNoteContainer.classList.remove('hidden');
                } else {
                    failNoteContainer.classList.add('hidden');
                }
            }

            if (badgeContainer) {
                if (log.status === 'verified') {
                    badgeContainer.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-300 shadow-2xs font-mono">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>TERVERIFIKASI (VERIFIED)</span>
                        </span>`;
                } else if (log.status === 'failed') {
                    badgeContainer.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-300 shadow-2xs font-mono">
                            <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                            <span>VERIFIKASI GAGAL (FAILED)</span>
                        </span>`;
                } else {
                    badgeContainer.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-300 shadow-2xs font-mono">
                            <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                            <span>MENUNGGU REVIEW (PENDING)</span>
                        </span>`;
                }
            }

            const container = document.getElementById('hero-verification-container');
            if (container) {
                container.classList.add('ring-2', 'ring-indigo-400');
                setTimeout(() => container.classList.remove('ring-2', 'ring-indigo-400'), 800);
            }
        }

        function prependLiveStreamTimeline(log) {
            const feed = document.getElementById('live-stream-feed');
            if (!feed) return;

            const card = document.createElement('div');
            card.className = `p-3 rounded-lg border border-slate-200/80 bg-slate-50/50 hover:bg-slate-50 transition-all animate-fadeIn space-y-2 ${log.status === 'failed' ? 'border-l-4 border-l-rose-500' : 'border-l-4 border-l-emerald-500'}`;
            card.innerHTML = `
                <div class="flex items-center gap-2">
                    <img src="${log.photo_url}" alt="Thumb" class="w-8 h-8 rounded-lg object-cover border border-slate-200 shrink-0">
                    <div class="min-w-0">
                        <div class="font-bold text-xs text-slate-900 truncate">${log.subject_name}</div>
                        <div class="text-[10px] text-slate-500 font-mono">${new Date().toLocaleTimeString('id-ID')}</div>
                    </div>
                </div>
                <div class="flex justify-between items-center text-[10px] font-mono pt-1 border-t border-slate-200/60">
                    <span class="font-bold ${log.status === 'failed' ? 'text-rose-600' : 'text-emerald-600'}">
                        ${log.status.toUpperCase()}
                    </span>
                    <span class="text-slate-500">${log.confidence_score}%</span>
                </div>
            `;
            feed.insertBefore(card, feed.firstChild);
            if (feed.children.length > 5) {
                feed.lastElementChild.remove();
            }
        }

        function prependLogRow(log) {
            const tbody = document.getElementById('logs-table-body');
            const mobContainer = document.getElementById('logs-mobile-container');
            const dateStr = new Date().toLocaleTimeString('id-ID');

            let statusBadge = log.status === 'verified'
                ? `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono"><i class="fa-solid fa-check text-[10px]"></i> VERIFIED</span>`
                : (log.status === 'failed'
                    ? `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 font-mono"><i class="fa-solid fa-xmark text-[10px]"></i> FAILED</span>`
                    : `<span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 font-mono"><i class="fa-solid fa-clock text-[10px]"></i> PENDING</span>`);

            if (tbody) {
                const tr = document.createElement('tr');
                tr.id = `row-log-${log.id}`;
                tr.className = `hover:bg-slate-50/60 transition-colors animate-fadeIn ${log.status === 'failed' ? 'bg-rose-50/20' : ''}`;
                tr.innerHTML = `
                    <td class="py-3 px-3.5 font-mono text-[11px] text-slate-500 whitespace-nowrap">${dateStr}</td>
                    <td class="py-3 px-3.5 whitespace-nowrap">
                        <img src="${log.photo_url}" alt="Thumb" class="w-9 h-9 rounded-lg object-cover border border-slate-200 shadow-2xs">
                    </td>
                    <td class="py-3 px-3.5">
                        <div class="font-bold text-slate-900">${log.subject_name}</div>
                        <div class="flex items-center gap-1.5 text-[11px] text-slate-500 font-mono">
                            <span>${log.nim || 'N/A'}</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-slate-600 font-semibold">${log.category || 'Taruna'}</span>
                        </div>
                    </td>
                    <td class="py-3 px-3.5">
                        <div class="text-slate-800">${log.location || 'Gate Utama'}</div>
                        <div class="text-[10px] font-mono text-slate-400">${log.device_id}</div>
                    </td>
                    <td class="py-3 px-3.5 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <span class="font-bold font-mono text-xs text-slate-800">${log.confidence_score}%</span>
                            <div class="w-12 bg-slate-200 rounded-full h-1.5">
                                <div class="h-full rounded-full ${log.status === 'failed' ? 'bg-rose-500' : 'bg-emerald-500'}" style="width: ${Math.min(100, log.confidence_score)}%"></div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-3.5 whitespace-nowrap">${statusBadge}</td>
                    <td class="py-3 px-3.5 text-right font-mono text-[11px] text-slate-600 whitespace-nowrap">${log.latency_ms} ms</td>
                    <td class="py-3 px-3.5 text-center whitespace-nowrap">
                        <div class="inline-flex items-center gap-1">
                            <button onclick="quickOverride(${log.id}, 'approve')" title="Setujui Akses" class="w-7 h-7 rounded-lg flex items-center justify-center bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-colors">
                                <i class="fa-solid fa-check text-[11px]"></i>
                            </button>
                            <button onclick="quickOverride(${log.id}, 'reject')" title="Tolak / Flag" class="w-7 h-7 rounded-lg flex items-center justify-center bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors">
                                <i class="fa-solid fa-ban text-[11px]"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.insertBefore(tr, tbody.firstChild);
            }

            if (mobContainer) {
                const mobCard = document.createElement('div');
                mobCard.id = `mob-log-${log.id}`;
                mobCard.className = `p-3.5 rounded-xl border border-slate-200 bg-white shadow-2xs space-y-2 ${log.status === 'failed' ? 'border-l-4 border-l-rose-500 bg-rose-50/20' : 'border-l-4 border-l-emerald-500'}`;
                mobCard.innerHTML = `
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex items-center gap-2.5">
                            <img src="${log.photo_url}" alt="Thumb" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shrink-0">
                            <div>
                                <div class="font-bold text-xs text-slate-900 line-clamp-1">${log.subject_name}</div>
                                <div class="text-[10px] text-slate-500 font-mono">${log.nim || 'N/A'} • ${log.category || 'Taruna'}</div>
                            </div>
                        </div>
                        <div>${statusBadge}</div>
                    </div>
                    <div class="flex items-center justify-between text-[10px] text-slate-500 pt-1 border-t border-slate-100 font-mono">
                        <span>${log.location}</span>
                        <span>Confidence: <strong class="text-slate-800">${log.confidence_score}%</strong></span>
                        <span>Latensi: <strong class="text-slate-800">${log.latency_ms} ms</strong></span>
                    </div>
                `;
                mobContainer.insertBefore(mobCard, mobContainer.firstChild);
            }
        }

        async function pollVerificationStats() {
            try {
                const res = await fetch('/api/verifications/stats');
                if (res.ok) {
                    const data = await res.json();
                    
                    const kpiTotal = document.getElementById('kpi-total-today');
                    const kpiVerified = document.getElementById('kpi-verified-count');
                    const kpiFailed = document.getElementById('kpi-failed-count');
                    const kpiConf = document.getElementById('kpi-avg-confidence');
                    const kpiLat = document.getElementById('kpi-avg-latency');
                    const sideBadge = document.getElementById('sidebar-total-badge');

                    if (kpiTotal) kpiTotal.innerText = data.total_today;
                    if (kpiVerified) kpiVerified.innerText = data.verified_count;
                    if (kpiFailed) kpiFailed.innerText = data.failed_count;
                    if (kpiConf) kpiConf.innerText = `${data.avg_confidence}%`;
                    if (kpiLat) kpiLat.innerText = `${data.avg_latency_ms} ms`;
                    if (sideBadge) sideBadge.innerText = data.total_today;
                }
            } catch (e) {}
        }

        async function triggerSimulatedScan(type = 'random') {
            try {
                const res = await fetch('/api/verifications/simulate', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ type: type })
                });
                const data = await res.json();
                if (data.success) {
                    showToast(`Simulasi berhasil dikirim (${type})`, 'success');
                }
            } catch (e) {
                showToast('Gagal mengirim simulasi', 'error');
            }
        }

        async function handleManualOverride(action) {
            if (!latestLogId) {
                showToast('Belum ada data verifikasi yang dipilih', 'warning');
                return;
            }
            await quickOverride(latestLogId, action);
        }

        async function quickOverride(id, action) {
            try {
                const res = await fetch(`/api/verifications/${id}/manual-action`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify({ action: action })
                });
                const data = await res.json();
                if (data.success) {
                    showToast(`Akses berhasil ${action === 'approve' ? 'diotorisasi (Verified)' : 'ditolak (Failed)'}`, 'success');
                    pollVerificationStats();
                }
            } catch (e) {
                showToast('Gagal memproses aksi operator', 'error');
            }
        }

        function filterByStatus(status) {
            ['all', 'verified', 'failed', 'pending'].forEach(s => {
                const btn = document.getElementById(`tab-filter-${s}`);
                if (btn) {
                    btn.className = (s === status)
                        ? "px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-2xs font-bold transition-all"
                        : "px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all";
                }
            });

            const rows = document.querySelectorAll('#logs-table-body tr');
            rows.forEach(r => {
                r.style.display = (status === 'all' || r.innerText.toLowerCase().includes(status)) ? '' : 'none';
            });
        }

        function handleSearchLogs(keyword) {
            const k = keyword.toLowerCase().trim();
            const rows = document.querySelectorAll('#logs-table-body tr');
            rows.forEach(r => {
                r.style.display = (r.innerText.toLowerCase().includes(k)) ? '' : 'none';
            });
        }

        // TCT Task Runner
        function selectAndStartTask(taskVal) {
            const select = document.getElementById('usability-task-select');
            if (select) select.value = taskVal;
            if (!isTaskRunning) {
                toggleUsabilityTask();
            }
        }

        async function toggleUsabilityTask() {
            const btn = document.getElementById('btn-start-usability-task');
            const icon = document.getElementById('icon-task-btn');
            const text = document.getElementById('text-task-btn');
            const timerDisplay = document.getElementById('usability-timer-display');
            const misclickDisplay = document.getElementById('usability-misclick-display');
            const taskSelect = document.getElementById('usability-task-select');

            if (!isTaskRunning) {
                isTaskRunning = true;
                taskStartTime = Date.now();
                taskMisclicks = 0;
                taskClicks = 0;
                if (misclickDisplay) misclickDisplay.innerText = '0';

                const [code, name] = taskSelect.value.split('|');

                try {
                    const res = await fetch('/api/usability/session/start', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                        body: JSON.stringify({
                            operator_name: "{{ auth()->user()->name ?? 'Letnan Dua Antok' }}",
                            task_code: code,
                            task_name: name
                        })
                    });
                    const data = await res.json();
                    activeTaskSessionId = data.session_id;
                } catch (e) {}

                if (btn) btn.className = "px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 active:scale-95 transition-all flex items-center gap-1.5 shadow-xs animate-pulse";
                if (icon) icon.className = "fa-solid fa-stop text-[11px]";
                if (text) text.innerText = "Selesaikan Tugas";

                taskTimerInterval = setInterval(() => {
                    const elapsed = (Date.now() - taskStartTime) / 1000;
                    const mins = Math.floor(elapsed / 60).toString().padStart(2, '0');
                    const secs = (elapsed % 60).toFixed(1).padStart(4, '0');
                    if (timerDisplay) timerDisplay.innerText = `${mins}:${secs}`;
                }, 100);

                showToast(`Pengujian ${code} dimulai! Lakukan aksi di dashboard.`, 'info');

            } else {
                isTaskRunning = false;
                clearInterval(taskTimerInterval);

                const finalTimeSec = ((Date.now() - taskStartTime) / 1000).toFixed(2);

                if (activeTaskSessionId) {
                    try {
                        await fetch('/api/usability/session/finish', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                            body: JSON.stringify({
                                session_id: activeTaskSessionId,
                                error_count: taskMisclicks,
                                clicks_count: taskClicks,
                                status: 'completed'
                            })
                        });
                    } catch (e) {}
                }

                if (btn) btn.className = "px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all flex items-center gap-1.5 shadow-xs";
                if (icon) icon.className = "fa-solid fa-play text-[11px]";
                if (text) text.innerText = "Mulai Uji Tugas";

                showToast(`Tugas selesai dalam ${finalTimeSec}s (Misclicks: ${taskMisclicks})`, 'success');
            }
        }

        function initMisclickTracker() {
            document.body.addEventListener('click', (e) => {
                if (isTaskRunning) {
                    taskClicks++;
                    if (!e.target.closest('button, a, input, select, tr, .clickable')) {
                        taskMisclicks++;
                        const misclickDisplay = document.getElementById('usability-misclick-display');
                        if (misclickDisplay) misclickDisplay.innerText = taskMisclicks;
                    }
                }
            });
        }

        // SUS Scoring Calculator (Inside Tab)
        function calculateTabLiveSus() {
            let oddSum = 0;
            let evenSum = 0;

            for (let i = 1; i <= 10; i++) {
                const checked = document.querySelector(`input[name="tab_q${i}"]:checked`);
                const val = checked ? parseInt(checked.value) : 3;
                if (i % 2 === 1) oddSum += (val - 1);
                else evenSum += (5 - val);
            }

            const totalScore = (oddSum + evenSum) * 2.5;

            const scoreEl = document.getElementById('tab-live-sus-score');
            const gradeEl = document.getElementById('tab-live-sus-grade');
            const targetEl = document.getElementById('tab-live-sus-target');

            if (scoreEl) scoreEl.innerText = totalScore.toFixed(1);

            let gradeText = 'GRADE F (POOR)';
            let gradeClass = 'ml-2 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-rose-900/80 text-rose-300 border border-rose-600 font-mono';

            if (totalScore >= 85.0) {
                gradeText = 'GRADE A+ (BEST IMAGINABLE)';
                gradeClass = 'ml-2 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-900/80 text-emerald-300 border border-emerald-600 font-mono';
            } else if (totalScore >= 80.3) {
                gradeText = 'GRADE A (EXCELLENT)';
                gradeClass = 'ml-2 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-900/80 text-emerald-300 border border-emerald-600 font-mono';
            } else if (totalScore >= 74.0) {
                gradeText = 'GRADE B (GOOD / ACCEPTABLE)';
                gradeClass = 'ml-2 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-teal-900/80 text-teal-300 border border-teal-600 font-mono';
            } else if (totalScore >= 68.0) {
                gradeText = 'GRADE C (OK)';
                gradeClass = 'ml-2 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-amber-900/80 text-amber-300 border border-amber-600 font-mono';
            }

            if (gradeEl) {
                gradeEl.innerText = gradeText;
                gradeEl.className = gradeClass;
            }

            if (targetEl) {
                if (totalScore >= 75.0) {
                    targetEl.innerHTML = `<i class="fa-solid fa-circle-check"></i> TARGET &gt;75 TERPENUHI`;
                    targetEl.className = "px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/30";
                } else {
                    targetEl.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> DI BAWAH TARGET &gt;75`;
                    targetEl.className = "px-2.5 py-1 rounded-lg bg-rose-500/20 text-rose-300 font-bold border border-rose-500/30";
                }
            }
        }

        async function handleTabSusSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-tab-sus');
            btn.disabled = true;
            btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...`;

            const formData = new FormData(document.getElementById('tab-sus-form'));
            const payload = {
                respondent_name: formData.get('respondent_name'),
                respondent_role: formData.get('respondent_role'),
                q1: parseInt(formData.get('tab_q1')),
                q2: parseInt(formData.get('tab_q2')),
                q3: parseInt(formData.get('tab_q3')),
                q4: parseInt(formData.get('tab_q4')),
                q5: parseInt(formData.get('tab_q5')),
                q6: parseInt(formData.get('tab_q6')),
                q7: parseInt(formData.get('tab_q7')),
                q8: parseInt(formData.get('tab_q8')),
                q9: parseInt(formData.get('tab_q9')),
                q10: parseInt(formData.get('tab_q10')),
                feedback: formData.get('feedback'),
            };

            try {
                const res = await fetch('/api/usability/sus/submit', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (data.success) {
                    showToast(`Kuesioner SUS Disimpan! Skor: ${data.score} (${data.adjective})`, 'success');
                    btn.disabled = false;
                    btn.innerHTML = `<i class="fa-solid fa-paper-plane"></i> <span>Simpan Kuesioner SUS</span>`;
                    switchTab('analytics');
                } else {
                    showToast('Gagal menyimpan kuesioner', 'error');
                    btn.disabled = false;
                }
            } catch (err) {
                showToast('Terjadi kesalahan jaringan', 'error');
                btn.disabled = false;
            }
        }

        // Fetch Analytics Stats via API
        async function fetchUsabilityStats() {
            try {
                const res = await fetch('/api/usability/stats');
                if (res.ok) {
                    const data = await res.json();
                    
                    const statAvgSus = document.getElementById('stat-avg-sus');
                    const statTotalResp = document.getElementById('stat-total-respondents');
                    const statAvgTct = document.getElementById('stat-avg-tct');
                    const statAvgLat = document.getElementById('stat-avg-ws-lat');

                    if (statAvgSus) statAvgSus.innerText = data.sus_summary.avg_score;
                    if (statTotalResp) statTotalResp.innerText = data.sus_summary.total_respondents;
                    if (statAvgTct) statAvgTct.innerText = `${data.tct_summary.avg_completion_time_sec} dtk`;
                    if (statAvgLat) statAvgLat.innerText = `${data.websocket_latency_summary.avg_latency_ms} ms`;

                    // Render Table Rows
                    const tbody = document.getElementById('table-sus-responses-body');
                    if (tbody && data.all_sus_responses) {
                        if (data.all_sus_responses.length === 0) {
                            tbody.innerHTML = `<tr><td colspan="13" class="text-center py-4 text-slate-400 font-sans">Belum ada respon kuesioner.</td></tr>`;
                        } else {
                            tbody.innerHTML = data.all_sus_responses.map(r => `
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="py-2.5 px-3 font-sans">
                                        <div class="font-bold text-slate-900">${r.respondent_name}</div>
                                        <div class="text-[10px] text-slate-500">${r.respondent_role}</div>
                                    </td>
                                    <td class="py-2.5 px-1.5 text-center">${r.q1}</td>
                                    <td class="py-2.5 px-1.5 text-center">${r.q2}</td>
                                    <td class="py-2.5 px-1.5 text-center">${r.q3}</td>
                                    <td class="py-2.5 px-1.5 text-center">${r.q4}</td>
                                    <td class="py-2.5 px-1.5 text-center">${r.q5}</td>
                                    <td class="py-2.5 px-1.5 text-center">${r.q6}</td>
                                    <td class="py-2.5 px-1.5 text-center">${r.q7}</td>
                                    <td class="py-2.5 px-1.5 text-center">${r.q8}</td>
                                    <td class="py-2.5 px-1.5 text-center">${r.q9}</td>
                                    <td class="py-2.5 px-1.5 text-center">${r.q10}</td>
                                    <td class="py-2.5 px-3 text-right font-bold text-slate-900">${r.final_score}</td>
                                    <td class="py-2.5 px-3 font-sans">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold ${r.final_score >= 75 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200'}">
                                            ${r.grade} (${r.adjective_rating})
                                        </span>
                                    </td>
                                </tr>
                            `).join('');
                        }
                    }
                }
            } catch (e) {}
        }

        // Web Audio Tone
        function playVerificationTone(status) {
            try {
                const ctx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);

                if (status === 'verified') {
                    osc.frequency.setValueAtTime(880, ctx.currentTime);
                    gain.gain.setValueAtTime(0.06, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.12);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.12);
                } else if (status === 'failed') {
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(220, ctx.currentTime);
                    gain.gain.setValueAtTime(0.1, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.25);
                    osc.start();
                    osc.stop(ctx.currentTime + 0.25);
                }
            } catch (e) {}
        }

        // Toast Messages
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            let color = 'bg-slate-900 text-white border-slate-800';
            let icon = 'fa-circle-info text-indigo-400';

            if (type === 'success') {
                color = 'bg-emerald-900 text-emerald-100 border-emerald-700';
                icon = 'fa-circle-check text-emerald-400';
            } else if (type === 'warning' || type === 'error') {
                color = 'bg-rose-900 text-rose-100 border-rose-700';
                icon = 'fa-triangle-exclamation text-rose-400';
            }

            toast.className = `flex items-center gap-2.5 px-4 py-2.5 rounded-xl border shadow-lg text-xs font-mono transition-all duration-300 transform translate-x-10 opacity-0 pointer-events-auto ${color}`;
            toast.innerHTML = `<i class="fa-solid ${icon}"></i><span>${message}</span>`;

            container.appendChild(toast);
            setTimeout(() => toast.classList.remove('translate-x-10', 'opacity-0'), 30);
            setTimeout(() => {
                toast.classList.add('translate-x-10', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }
    </script>

</body>
</html>
