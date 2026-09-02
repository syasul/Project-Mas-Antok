<!-- MAIN JAVASCRIPT CONTROL -->
<script>
    let activeServerState = 'normal';
    let isDdosActive = false;
    let selectedSector = 'Alpha';
    let serverOfflineMode = false;

    // UI/UX Evaluator testing variables
    let isTestRunning = false;
    let currentTaskId = 0;
    let testStartTime = null;
    let testTimerInterval = null;
    let testMisclicks = 0;
    let latencyHistory = []; // stores last 10 latency measurements

    // Web Audio API Tactical Sound Engine
    let isAudioSirenEnabled = true;
    let audioCtx = null;

    function getAudioContext() {
        if (!audioCtx) {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            audioCtx = new AudioContext();
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
        return audioCtx;
    }

    function toggleSirenAudio() {
        isAudioSirenEnabled = !isAudioSirenEnabled;
        const btn = document.getElementById('btn-toggle-siren-audio');
        const icon = document.getElementById('siren-audio-icon');
        const text = document.getElementById('siren-audio-text');
        if (isAudioSirenEnabled) {
            if (btn) btn.className = "flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl border text-[11px] font-semibold transition-all bg-emerald-50 text-emerald-700 border-emerald-300 hover:bg-emerald-100 shadow-sm";
            if (icon) icon.className = "fa-solid fa-volume-high text-xs text-emerald-600";
            if (text) text.innerText = "Sirene Audio: ON";
            playToneBeep(880, 0.1);
        } else {
            if (btn) btn.className = "flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl border text-[11px] font-semibold transition-all bg-slate-100 text-slate-500 border-slate-300 hover:bg-slate-200 shadow-sm";
            if (icon) icon.className = "fa-solid fa-volume-xmark text-xs text-slate-500";
            if (text) text.innerText = "Sirene Audio: MUTE";
        }
    }

    function playToneBeep(freq = 880, duration = 0.15) {
        if (!isAudioSirenEnabled) return;
        try {
            const ctx = getAudioContext();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(freq, ctx.currentTime);
            gain.gain.setValueAtTime(0.15, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + duration);
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + duration);
        } catch (e) {}
    }

    function playTacticalSiren(type = 'alarm') {
        if (!isAudioSirenEnabled) return;
        try {
            const ctx = getAudioContext();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sawtooth';
            
            const now = ctx.currentTime;
            const duration = 2.4;

            if (type === 'intruder' || type === 'alarm') {
                // Military Air Raid Frequency Sweep
                osc.frequency.setValueAtTime(440, now);
                osc.frequency.exponentialRampToValueAtTime(880, now + 0.6);
                osc.frequency.exponentialRampToValueAtTime(440, now + 1.2);
                osc.frequency.exponentialRampToValueAtTime(880, now + 1.8);
                osc.frequency.exponentialRampToValueAtTime(440, now + 2.4);
            } else if (type === 'breach' || type === 'uav') {
                // Dual-tone pulsed alarm
                osc.frequency.setValueAtTime(700, now);
                osc.frequency.setValueAtTime(550, now + 0.3);
                osc.frequency.setValueAtTime(700, now + 0.6);
                osc.frequency.setValueAtTime(550, now + 0.9);
                osc.frequency.setValueAtTime(700, now + 1.2);
                osc.frequency.setValueAtTime(550, now + 1.5);
                osc.frequency.setValueAtTime(700, now + 1.8);
            } else {
                // Warning Pulse
                osc.frequency.setValueAtTime(350, now);
                osc.frequency.exponentialRampToValueAtTime(600, now + 0.4);
                osc.frequency.exponentialRampToValueAtTime(350, now + 0.8);
            }

            gain.gain.setValueAtTime(0.18, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + duration);

            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(now);
            osc.stop(now + duration);
        } catch (e) {}
    }

    // Leaflet GIS Map state
    let leafletMap = null;
    let currentMapMode = 'svg';

    function switchMapMode(mode) {
        currentMapMode = mode;
        const btnSvg = document.getElementById('btn-map-mode-svg');
        const btnGis = document.getElementById('btn-map-mode-gis');
        const svgContainer = document.getElementById('map-blueprint-container');
        const gisContainer = document.getElementById('map-gis-container');

        if (mode === 'svg') {
            if (btnSvg) btnSvg.className = "px-2.5 py-1 rounded-md bg-white text-indigo-600 shadow-sm transition-all flex items-center gap-1.5";
            if (btnGis) btnGis.className = "px-2.5 py-1 rounded-md text-slate-500 hover:text-slate-800 transition-all flex items-center gap-1.5";
            if (svgContainer) svgContainer.classList.remove('hidden');
            if (gisContainer) gisContainer.classList.add('hidden');
        } else {
            if (btnGis) btnGis.className = "px-2.5 py-1 rounded-md bg-white text-indigo-600 shadow-sm transition-all flex items-center gap-1.5";
            if (btnSvg) btnSvg.className = "px-2.5 py-1 rounded-md text-slate-500 hover:text-slate-800 transition-all flex items-center gap-1.5";
            if (svgContainer) svgContainer.classList.add('hidden');
            if (gisContainer) gisContainer.classList.remove('hidden');

            setTimeout(() => {
                initLeafletPoltekadMap();
                if (leafletMap) leafletMap.invalidateSize();
            }, 100);
        }
    }

    function initLeafletPoltekadMap() {
        if (leafletMap) return;
        const mapEl = document.getElementById('leaflet-poltekad-map');
        if (!mapEl || typeof L === 'undefined') return;

        // Coordinates for Kesatrian Poltekad Kodiklatad
        const poltekadCenter = [-7.8893, 112.5401];

        leafletMap = L.map('leaflet-poltekad-map', {
            center: poltekadCenter,
            zoom: 16,
            zoomControl: true,
        });

        // Crisp tactical map layer
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; CARTO &bull; Poltekad Kodiklatad Security',
            maxZoom: 19
        }).addTo(leafletMap);

        // Sector Alpha Polygon
        L.polygon([
            [-7.8872, 112.5385],
            [-7.8872, 112.5415],
            [-7.8892, 112.5415],
            [-7.8892, 112.5385]
        ], {
            color: '#6366f1',
            fillColor: '#6366f1',
            fillOpacity: 0.15,
            weight: 2,
            dashArray: '4, 4'
        }).addTo(leafletMap).bindPopup("<strong class='text-indigo-600'>SEKTOR ALPHA</strong><br>Pos Perimeter Utara & Gerbang Utama Poltekad");

        // Sector Beta Polygon
        L.polygon([
            [-7.8894, 112.5385],
            [-7.8894, 112.5420],
            [-7.8914, 112.5420],
            [-7.8914, 112.5385]
        ], {
            color: '#06b6d4',
            fillColor: '#06b6d4',
            fillOpacity: 0.15,
            weight: 2,
            dashArray: '4, 4'
        }).addTo(leafletMap).bindPopup("<strong class='text-cyan-600'>SEKTOR BETA</strong><br>Pos Perimeter Selatan & Hangar Drone");

        // Turret Firing Radius (200m)
        L.circle([-7.8883, 112.5412], {
            color: '#f59e0b',
            fillColor: '#f59e0b',
            fillOpacity: 0.1,
            radius: 200,
            weight: 1.5,
            dashArray: '5, 5'
        }).addTo(leafletMap).bindPopup("<strong class='text-amber-600'>TURRET NORTH-WEST</strong><br>Radius Jangkauan Tembak: 200m");

        // Tactical Pins
        const createPin = (icon, color) => {
            return L.divIcon({
                className: 'custom-pin',
                html: `<div style="background:#0f172a; border:2px solid ${color}; color:${color}; width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 10px rgba(0,0,0,0.3); font-size:12px;">
                    <i class="${icon}"></i>
                </div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });
        };

        // HQ Marker
        L.marker(poltekadCenter, { icon: createPin('fa-solid fa-shield-halved', '#10b981') })
            .addTo(leafletMap).bindPopup("<strong>MARKAS KOMANDO (HQ)</strong><br>Pusat Kendali Operasi Terpadu");

        // Camera Gate Main
        L.marker([-7.8875, 112.5392], { icon: createPin('fa-solid fa-video', '#6366f1') })
            .addTo(leafletMap).bindPopup("<strong>CAM_GATE_MAIN</strong><br>Kamera AI Surveilans");

        // Turret North West
        L.marker([-7.8883, 112.5412], { icon: createPin('fa-solid fa-crosshairs', '#f59e0b') })
            .addTo(leafletMap).bindPopup("<strong>TURRET_NORTH_WEST</strong><br>Unit Turret Defensif");

        // Drone Hangar
        L.marker([-7.8905, 112.5398], { icon: createPin('fa-solid fa-helicopter', '#0ea5e9') })
            .addTo(leafletMap).bindPopup("<strong>DRONE_HANGAR_01</strong><br>Hangar Drone Patroli");

        // Drone Flight Route
        L.polyline([
            [-7.8905, 112.5398],
            [-7.8875, 112.5385],
            [-7.8872, 112.5415],
            [-7.8912, 112.5418],
            [-7.8905, 112.5398]
        ], {
            color: '#0ea5e9',
            weight: 2,
            dashArray: '6, 6'
        }).addTo(leafletMap);
    }

    // Telegram Emergency Dispatch Helper
    async function dispatchTelegramAlert() {
        try {
            const res = await fetch('/api/dashboard/send-telegram-alert', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    threat_type: 'EMERGENCY_SECURITY_ALERT',
                    location: `Sektor ${selectedSector} - Poltekad Command Center`
                })
            });
            const data = await res.json();
            playToneBeep(1200, 0.2);
            showTacticalToast(data.message || 'Notifikasi Telegram Terkirim');
        } catch (e) {
            showTacticalToast('Gagal memancarkan sinyal notifikasi Telegram', 'error');
        }
    }

    function showTacticalToast(msg, type = 'success') {
        const toast = document.createElement('div');
        const colorBg = type === 'success' ? 'bg-slate-900 border-emerald-500/50 text-emerald-400' : 'bg-rose-900 border-rose-500/50 text-rose-200';
        toast.className = `fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl border shadow-2xl backdrop-blur-md ${colorBg} text-xs font-mono transition-all duration-300 transform translate-y-2 opacity-0`;
        toast.innerHTML = `<i class="fa-solid fa-circle-check text-base"></i><span>${msg}</span>`;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.remove('translate-y-2', 'opacity-0');
        }, 50);
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    let liveEventSource = null;
    function initRealtimeSSE() {
        if (typeof window.EventSource !== 'undefined') {
            try {
                if (liveEventSource) liveEventSource.close();
                liveEventSource = new EventSource('/api/dashboard/stream');
                liveEventSource.addEventListener('sensor_log', (e) => {
                    pollSystemState();
                });
                liveEventSource.addEventListener('decision_log', (e) => {
                    pollSystemState();
                });
                liveEventSource.onerror = () => {
                    // Falls back gracefully to interval polling
                };
            } catch (e) {}
        }
    }

    window.addEventListener('load', () => {
        setInterval(updateClock, 1000);
        updateClock();

        initCameraAIFeed();
        initPerimeterWave();
        initRealtimeSSE();

        setInterval(pollSystemState, 2000);
        pollSystemState();

        document.body.addEventListener('click', handlePageClick, true);
    });

    // Helper to map sector name to element ID suffix
    function getSectorId(s) {
        if (s === 'Drone Hangar') return 'drone';
        if (s === 'Turret Tower') return 'turret';
        return s.toLowerCase();
    }

    // Responsive Mobile Sidebar Toggle
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('sidebar-nav');
        const backdrop = document.getElementById('sidebar-backdrop');
        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        }
    }

    // Tab Switcher
    function switchTab(tabId) {
        const tabs = ['overview', 'camera', 'drone', 'perimeter', 'iot', 'turret', 'decision'];
        
        // Toggle visibility of panels
        tabs.forEach(t => {
            const el = document.getElementById('tab-content-' + t);
            const nav = document.getElementById('nav-' + t);
            if (t === tabId) {
                if (el) el.classList.remove('hidden');
                if (nav) nav.className = "w-full flex items-center gap-3 px-3 py-2 text-xs font-semibold rounded-xl text-indigo-600 bg-indigo-50 transition-all border-l-2 border-indigo-600";
            } else {
                if (el) el.classList.add('hidden');
                if (nav) nav.className = "w-full flex items-center gap-3 px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition-all border-l-2 border-transparent";
            }
        });

        // Close mobile sidebar menu if it is currently open
        const sidebar = document.getElementById('sidebar-nav');
        const backdrop = document.getElementById('sidebar-backdrop');
        if (sidebar && !sidebar.classList.contains('-translate-x-full') && window.innerWidth < 1024) {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        }

        // Update Header Title & Subtitle based on active view
        const titles = {
            overview: ["PUSAT KOMANDO UTAMA", "Pemantau Koordinat Peta & Telemetri Sensor Terpadu"],
            camera: ["PEMANTAUAN SURVEILANS AI", "Kisi Deteksi & Klasifikasi Objek Pintar Terintegrasi"],
            drone: ["TELEMETRI DRONE PATROLI", "Navigasi Multirotor & Telemetri Uji Ruang Udara"],
            perimeter: ["SENSOR GETARAN PERIMETER", "Determinasi Seismik Sensor Pagar Sektor Pertahanan"],
            iot: ["GATEWAY UNIFIED KOMUNIKASI", "Rute Aliran Data MQTT, WebSockets, & REST API Gateway"],
            turret: ["UNIT DEFENSIVE TURRET", "Otorisasi Penembakan & Dial Rotasi Target Lock"],
            decision: ["LOG KEPUTUSAN ANCAMAN", "Log Rationale IF-THEN Otomatisasi Penyelamatan"]
        };

        const pageTitle = document.getElementById('page-title');
        const pageSubtitle = document.getElementById('page-subtitle');
        if (pageTitle && titles[tabId]) pageTitle.innerText = titles[tabId][0];
        if (pageSubtitle && titles[tabId]) pageSubtitle.innerText = titles[tabId][1];

        // Re-draw canvas elements if tab changes
        if (tabId === 'camera') initCameraAIFeed();
        if (tabId === 'perimeter') initPerimeterWave();

        // If UI task 1 is running and user clicks nav-camera
        if (isTestRunning && currentTaskId === 1 && tabId === 'turret') {
            const btnLock = document.getElementById('btn-lock-target');
            if (btnLock) btnLock.classList.add('guide-highlight');
        }
    }

    // Clock
    function updateClock() {
        const now = new Date();
        const clockEl = document.getElementById('live-clock');
        if (clockEl) clockEl.innerText = now.toTimeString().split(' ')[0];
    }

    // Poll System Status
    async function pollSystemState() {
        if (activeServerState === 'down') {
            showServerOfflineUI();
            return;
        }

        const startFetch = performance.now();
        try {
            const response = await fetch('/api/dashboard/status');
            if (!response.ok) throw new Error("API server crash");
            const data = await response.json();
            const endFetch = performance.now();
            const rtt = Math.round(endFetch - startFetch);
            
            const banner = document.getElementById('connection-error-banner');
            const asideStatus = document.getElementById('aside-system-status');
            if (banner) banner.classList.add('hidden');
            if (asideStatus) {
                asideStatus.innerText = "ONLINE";
                asideStatus.className = "font-bold text-emerald-600 uppercase";
            }
            serverOfflineMode = false;

            // Sync status elements
            const statLogs = document.getElementById('stat-total-logs');
            const statLatency = document.getElementById('stat-latency');
            if (statLogs) statLogs.innerText = data.metrics.total_logs;
            if (statLatency) statLatency.innerText = data.metrics.avg_latency_ms + ' ms';
            
            const cpuVal = document.getElementById('metric-cpu-val');
            const cpuBar = document.getElementById('metric-cpu-bar');
            if (cpuVal) cpuVal.innerText = data.metrics.cpu_usage_pct + '%';
            if (cpuBar) cpuBar.style.width = data.metrics.cpu_usage_pct + '%';

            const ramVal = document.getElementById('metric-ram-val');
            const ramBar = document.getElementById('metric-ram-bar');
            if (ramVal) ramVal.innerText = data.metrics.ram_usage_gb + ' / 16 GB';
            if (ramBar) ramBar.style.width = (data.metrics.ram_usage_gb / 16 * 100) + '%';

            const simState = document.getElementById('sim-server-state');
            if (simState && data.server_state !== activeServerState) {
                activeServerState = data.server_state;
                simState.value = activeServerState;
            }

            isDdosActive = data.ddos_simulation_mode;
            updateDdosUI(data.ddos_lockout, data.metrics.total_logs);
            renderLogs(data.active_alerts, data.recent_decisions);

            // Sync Core KPI Metrics
            const kpiStatus = document.getElementById('kpi-status-state');
            const kpiThreat = document.getElementById('kpi-threat-count');
            const kpiThreatBg = document.getElementById('kpi-threat-icon-bg');
            const kpiThreatIcon = document.getElementById('kpi-threat-icon');
            const kpiTraffic = document.getElementById('kpi-traffic-rate');

            if (kpiStatus) {
                if (data.active_alerts.length > 0) {
                    kpiStatus.innerText = "SIAGA 3 (ALERT)";
                    kpiStatus.className = "block text-xs font-bold text-rose-600 animate-pulse";
                } else {
                    kpiStatus.innerText = "SIAGA 1 (SECURE)";
                    kpiStatus.className = "block text-xs font-bold text-slate-800";
                }
            }

            if (kpiThreat) {
                kpiThreat.innerText = data.active_alerts.length + " Terdeteksi";
                if (data.active_alerts.length > 0) {
                    kpiThreat.className = "block text-xs font-bold text-rose-600";
                    if (kpiThreatBg) kpiThreatBg.className = "w-11 h-11 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 shadow-sm shrink-0";
                    if (kpiThreatIcon) kpiThreatIcon.className = "fa-solid fa-triangle-exclamation text-lg text-rose-500 animate-bounce";
                } else {
                    kpiThreat.className = "block text-xs font-bold text-slate-800";
                    if (kpiThreatBg) kpiThreatBg.className = "w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm shrink-0";
                    if (kpiThreatIcon) kpiThreatIcon.className = "fa-solid fa-triangle-exclamation text-lg text-indigo-600";
                }
            }

            if (kpiTraffic) {
                const baseThroughput = 1100 + data.metrics.total_logs * 3;
                kpiTraffic.innerText = Math.round(baseThroughput + Math.random() * 40) + " Pkts / Detik";
            }

            // Sync Diagnostik Kuantitatif & Health Sensor
            updateLatencyUI(rtt);
            updateSensorHealthUI(data.sensor_health);
            updateSensorErrorLogsUI(data.sensor_error_logs);

        } catch (err) {
            showServerOfflineUI();
        }
    }

    function showServerOfflineUI() {
        const banner = document.getElementById('connection-error-banner');
        const asideStatus = document.getElementById('aside-system-status');
        if (banner) banner.classList.remove('hidden');
        if (asideStatus) {
            asideStatus.innerText = "OFFLINE";
            asideStatus.className = "font-bold text-rose-600 uppercase";
        }
        serverOfflineMode = true;

        const statLatency = document.getElementById('stat-latency');
        if (statLatency) statLatency.innerText = 'OFFLINE';

        const rawTbody = document.getElementById('cc-raw-log-tbody');
        if (rawTbody) {
            rawTbody.innerHTML = `
                <tr>
                    <td colspan="5" class="py-4 text-center text-rose-500 font-bold bg-rose-50/50">
                        <i class="fa-solid fa-triangle-exclamation mr-1"></i> HUBUNGAN TERPUTUS: MENCOBA TERHUBUNG KEMBALI...
                    </td>
                </tr>
            `;
        }

        // Sync Diagnostik Kuantitatif & Sensor Health to offline state
        updateLatencyUIOffline();
    }

    async function changeServerState(state) {
        activeServerState = state;
        try {
            await fetch('/api/dashboard/toggle-server-state', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ state: state })
            });
            if (state === 'down') showServerOfflineUI();
            else pollSystemState();
        } catch (e) {}
    }

    async function toggleDdosSimulation() {
        isDdosActive = !isDdosActive;
        try {
            await fetch('/api/gateway/toggle-ddos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ enabled: isDdosActive })
            });

            if (isDdosActive) floodGatewayRequests();
            else pollSystemState();
        } catch (e) {}
    }

    async function floodGatewayRequests() {
        if (!isDdosActive) return;
        for (let i = 0; i < 20; i++) {
            fetch('/api/gateway/receive', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    sensor_type: 'iot',
                    sensor_name: 'DDoS_Flood_' + i,
                    protocol: 'REST_API',
                    timestamp: Date.now() / 1000,
                    data: { packet_loss_pct: 0, malicious_activity_detected: false }
                })
            }).catch(() => {});
        }
        setTimeout(pollSystemState, 1000);
    }

    function updateDdosUI(lockedOut, totalLogs) {
        const btn = document.getElementById('btn-toggle-ddos');
        const pulse = document.getElementById('ddos-status-pulse');
        const tabLogInfo = document.getElementById('tab-ddos-log-info');

        if (isDdosActive) {
            if (btn) btn.className = 'border border-rose-500 bg-rose-50 text-rose-600 px-3 py-1.5 rounded-xl text-xs font-mono font-bold transition-all flex items-center gap-2 shadow-sm';
            if (pulse) pulse.className = 'w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping';

            if (lockedOut) {
                if (tabLogInfo) tabLogInfo.innerText = "CRITICAL: Heavy flooding detected. Automatic IP isolation triggered (503 Service Unavailable).";
                if (isTestRunning && currentTaskId === 2) completeUsabilityTask(2);
            } else {
                if (tabLogInfo) tabLogInfo.innerText = "WARNING: Suspicious high traffic from single IP address. Rate limiter warnings sent.";
            }
        } else {
            if (btn) btn.className = 'border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 px-3 py-1.5 rounded-xl text-xs font-mono font-bold transition-all flex items-center gap-2 shadow-sm';
            if (pulse) pulse.className = 'w-2.5 h-2.5 rounded-full bg-slate-300';
            if (tabLogInfo) tabLogInfo.innerText = "Normal state. No malicious packets detected.";
        }
    }

    function renderLogs(alerts, decisions) {
        // Render active threat list on CC overview
        const alertsList = document.getElementById('dashboard-active-alerts-list');
        if (alertsList) {
            if (alerts.length === 0) {
                alertsList.innerHTML = `<div class="p-3 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl text-xs font-semibold flex items-center gap-2"><i class="fa-solid fa-circle-check"></i> Seluruh Sektor Aman (Secure)</div>`;
            } else {
                alertsList.innerHTML = alerts.map(a => `
                    <div class="p-3 bg-rose-50 text-rose-700 border border-rose-100 rounded-xl text-xs flex flex-col gap-1 shadow-sm relative overflow-hidden animate-pulse">
                        <div class="absolute top-0 right-0 h-full w-1.5 bg-rose-500"></div>
                        <div class="flex justify-between items-center font-bold">
                            <span class="uppercase">${a.event_type}</span>
                            <span class="text-[9px] bg-rose-200 text-rose-800 px-1.5 py-0.5 rounded-full uppercase">${a.severity}</span>
                        </div>
                        <p class="text-[10px] text-slate-500 font-mono mt-0.5">${JSON.stringify(a.details)}</p>
                    </div>
                `).join('');
            }
        }

        // Render decisions
        const tbodies = [document.getElementById('decision-log-tbody'), document.getElementById('tab-decision-log-tbody')];
        tbodies.forEach(tbody => {
            if (!tbody) return;
            if (decisions.length === 0) {
                tbody.innerHTML = `<tr><td colspan="4" class="py-4 text-center text-slate-400">Tidak ada keputusan tindakan otomatis.</td></tr>`;
            } else {
                tbody.innerHTML = decisions.map(d => {
                    const time = new Date(d.created_at).toLocaleTimeString();
                    const rules = d.rules_applied.condition || 'IF generic_anomaly';
                    const actions = Object.entries(d.action_taken).map(([k, v]) => `${k}:${v}`).join(', ');
                    return `
                        <tr class="hover:bg-slate-50">
                            <td class="py-2.5 px-2 text-indigo-600 font-bold">${time}</td>
                            <td class="py-2.5 px-2 text-slate-700">${d.security_event ? d.security_event.event_type : 'Anomali Global'}</td>
                            <td class="py-2.5 px-2 text-slate-400">${rules}</td>
                            <td class="py-2.5 px-2 text-slate-800 font-bold">${actions}</td>
                        </tr>
                    `;
                }).join('');
            }
        });

        // Render raw logs
        const rawBodies = [document.getElementById('cc-raw-log-tbody'), document.getElementById('raw-log-tbody')];
        fetch('/api/dashboard/logs')
            .then(res => res.json())
            .then(logs => {
                rawBodies.forEach(tbody => {
                    if (!tbody) return;
                    if (logs.length === 0) {
                        tbody.innerHTML = `<tr><td colspan="5" class="py-4 text-center text-slate-400">Belum ada data sensor.</td></tr>`;
                    } else {
                        tbody.innerHTML = logs.map(l => {
                            const time = new Date(l.created_at).toLocaleTimeString();
                            let labelColor = 'text-indigo-600';
                            if (l.protocol === 'WebSocket') labelColor = 'text-sky-600';
                            if (l.protocol === 'REST_API') labelColor = 'text-teal-600';
                            return `
                                <tr class="hover:bg-slate-50">
                                    <td class="py-2 px-2 text-slate-400">${time}</td>
                                    <td class="py-2 px-2 ${labelColor} font-bold">${l.protocol}</td>
                                    <td class="py-2 px-2 text-slate-700 font-bold uppercase">${l.sensor_type}</td>
                                    <td class="py-2 px-2 text-slate-800 font-semibold">${l.latency_ms} ms</td>
                                    <td class="py-2 px-2 text-slate-400 text-[10px] truncate max-w-xs">${JSON.stringify(l.data)}</td>
                                </tr>
                            `;
                        }).join('');
                    }
                });
            }).catch(() => {});
    }

    // Map Sektor selection
    function selectSector(sectorName) {
        selectedSector = sectorName;
        const badge = document.getElementById('selected-sector-badge');
        if (badge) badge.innerText = 'TERPILIH: ' + sectorName.toUpperCase();

        const titleEl = document.getElementById('selected-sector-title');
        if (titleEl) titleEl.innerText = sectorName.toUpperCase();

        // Update Sector Details Briefing Widget
        const sectorDetails = {
            'Alpha': {
                risk: 'RENDAH (LOW)',
                riskClass: 'text-emerald-600 font-bold font-mono',
                cam: 'CAM_101 (Aktif)',
                perim: 'PERIM_S2 (Aktif)',
                turret: 'TURRET_1 (Standby)',
                desc: 'Sektor Alpha mencakup pertahanan perimeter luar bagian utara, dilengkapi kamera AI klasifikasi target dan sensor getaran fiber-optik.'
            },
            'Beta': {
                risk: 'RENDAH (LOW)',
                riskClass: 'text-emerald-600 font-bold font-mono',
                cam: 'CAM_202 (Standby)',
                perim: 'PERIM_S3 (Aktif)',
                turret: 'TURRET_2 (Standby)',
                desc: 'Sektor Beta mengontrol garis perimeter luar bagian selatan, terhubung ke sensor getaran pagar sekunder dan rute patroli drone utama.'
            },
            'Drone Hangar': {
                risk: 'AMAN (NONE)',
                riskClass: 'text-emerald-600 font-bold font-mono',
                cam: 'CAM_HANGAR (Aktif)',
                perim: 'N/A',
                turret: 'N/A',
                desc: 'Hangar pemeliharaan dan peluncuran unit drone patroli taktis udara (Recon Multirotor).'
            },
            'Turret Tower': {
                risk: 'RENDAH (LOW)',
                riskClass: 'text-emerald-600 font-bold font-mono',
                cam: 'CAM_TOWER (Aktif)',
                perim: 'N/A',
                turret: 'TURRET_MAIN (Standby)',
                desc: 'Menara pertahanan utama bersenjata turret otomatis dengan kendali rotasi servo terkomputerisasi.'
            },
            'HQ': {
                risk: 'AMAN (NONE)',
                riskClass: 'text-emerald-600 font-bold font-mono',
                cam: 'CAM_HQ (Aktif)',
                perim: 'N/A',
                turret: 'N/A',
                desc: 'Pusat komando dan koordinasi operasional taktis Poltekad, memproses seluruh aliran sensor terpadu.'
            }
        };

        const secInfo = sectorDetails[sectorName];
        if (secInfo) {
            const nameEl = document.getElementById('detail-sector-name');
            const riskEl = document.getElementById('detail-sector-risk');
            const camEl = document.getElementById('detail-sector-cam');
            const perimEl = document.getElementById('detail-sector-perim');
            const turretEl = document.getElementById('detail-sector-turret');
            const descEl = document.getElementById('detail-sector-desc');

            if (nameEl) nameEl.innerText = sectorName.toUpperCase();
            if (riskEl) {
                riskEl.innerText = secInfo.risk;
                riskEl.className = secInfo.riskClass;
            }
            if (camEl) camEl.innerText = secInfo.cam;
            if (perimEl) perimEl.innerText = secInfo.perim;
            if (turretEl) turretEl.innerText = secInfo.turret;
            if (descEl) descEl.innerText = secInfo.desc;
        }

        // Highlight shapes
        const sectors = ['Alpha', 'Beta', 'Drone Hangar', 'Turret Tower', 'HQ'];
        sectors.forEach(s => {
            const el = document.getElementById('sector-' + getSectorId(s));
            if (el) {
                if (s === sectorName) {
                    el.classList.add('fill-indigo-50/70', 'stroke-indigo-500');
                    el.setAttribute('stroke-width', '2');
                } else {
                    el.classList.remove('fill-indigo-50/70', 'stroke-indigo-500');
                    el.setAttribute('stroke-width', '1');
                }
            }
        });

        const coordsEl = document.getElementById('camera-feed-coordinates');
        if (coordsEl) {
            if (sectorName === 'Alpha') coordsEl.innerText = "LAT: -7.5301  LON: 110.2304";
            else if (sectorName === 'Beta') coordsEl.innerText = "LAT: -7.5325  LON: 110.2335";
        }

        if (isTestRunning && currentTaskId === 3 && sectorName === 'Beta') {
            const btnDrone = document.getElementById('btn-deploy-drone');
            if (btnDrone) btnDrone.classList.add('guide-highlight');
            const btnDroneTab = document.getElementById('btn-deploy-drone-tab');
            if (btnDroneTab) btnDroneTab.classList.add('guide-highlight');
        }
    }

    async function triggerSimulatedThreat(type) {
        if (serverOfflineMode) return;
        try {
            playTacticalSiren(type);

            await fetch('/api/dashboard/trigger-mock-event', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ type: type, sector: selectedSector })
            });

            flashMapThreat(type);
            pollSystemState();

            const threatLabels = {
                intruder: 'Penyusup Bersenjata Terdeteksi',
                breach: 'Pembobolan Pagar Perimeter',
                uav: 'Drone Tak Dikenal Melintas',
                iot_attack: 'Anomali Serangan Paket IoT',
                turret_fail: 'Malfungsi Sistem Turret'
            };
            showTacticalToast(`🚨 [ALERT] ${threatLabels[type] || 'Ancaman Taktis Terdeteksi'} pada Sektor ${selectedSector}!`, 'error');

            if (isTestRunning && currentTaskId === 1 && type === 'intruder' && selectedSector === 'Alpha') {
                const navTurret = document.getElementById('nav-turret');
                if (navTurret) navTurret.classList.add('guide-highlight');
            }
        } catch (err) {}
    }

    function flashMapThreat(type) {
        let elId = '';
        if (type === 'intruder') elId = 'map-node-camera';
        if (type === 'breach') elId = 'map-node-perimeter';
        if (type === 'uav') elId = 'map-node-drone';
        if (type === 'turret_fail') elId = 'map-node-turret-ring';

        const el = document.getElementById(elId);
        if (el) {
            el.setAttribute(el.tagName === 'line' ? 'stroke' : 'fill', '#f43f5e');
            setTimeout(() => {
                el.setAttribute(el.tagName === 'line' ? 'stroke' : 'fill', elId.includes('turret') ? 'none' : '#10b981');
            }, 4000);
        }
    }

    // Drone action
    function deployDroneAction() {
        const mission = document.getElementById('drone-mission-status');
        const battery = document.getElementById('drone-battery');
        const altitude = document.getElementById('drone-altitude');
        const sector = document.getElementById('drone-sector');

        if (mission) {
            mission.innerText = "PATROLLING...";
            mission.className = "font-bold text-xs text-amber-600 animate-pulse";
        }
        if (battery) battery.innerText = "92%";
        if (altitude) altitude.innerText = "85 m";
        if (sector) sector.innerText = selectedSector;
        
        const tabAlt = document.getElementById('tab-drone-alt');
        const tabBat = document.getElementById('tab-drone-bat');
        const tabSec = document.getElementById('tab-drone-sec');
        if (tabAlt) tabAlt.innerText = "85 m";
        if (tabBat) tabBat.innerText = "92%";
        if (tabSec) tabSec.innerText = selectedSector + " (Patrol)";

        // Update Notification Widget
        const ccNotifBatVal = document.getElementById('cc-notif-drone-battery-val');
        const ccNotifBatBar = document.getElementById('cc-notif-drone-battery-bar');
        if (ccNotifBatVal) ccNotifBatVal.innerText = "92%";
        if (ccNotifBatBar) {
            ccNotifBatBar.style.width = "92%";
            ccNotifBatBar.className = "bg-amber-500 h-full transition-all duration-300";
        }

        fetch('/api/gateway/receive', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                sensor_type: 'drone',
                sensor_name: 'DRONE_RECON_UNIT',
                protocol: 'WebSocket',
                timestamp: Date.now() / 1000,
                data: { battery_pct: 92, altitude_m: 85, location: selectedSector, intrusion_detected: false }
            })
        }).then(() => pollSystemState()).catch(() => {});

        if (isTestRunning && currentTaskId === 3) completeUsabilityTask(3);

        setTimeout(() => {
            if (mission) {
                mission.innerText = "STANDBY";
                mission.className = "font-bold text-xs text-emerald-600";
            }
            if (altitude) altitude.innerText = "0 m";
            if (sector) sector.innerText = "Hangar";
            
            if (tabAlt) tabAlt.innerText = "0 m";
            if (tabBat) tabBat.innerText = "98%";
            if (tabSec) tabSec.innerText = "Hangar (Standby)";

            // Update Notification Widget
            if (ccNotifBatVal) ccNotifBatVal.innerText = "98%";
            if (ccNotifBatBar) {
                ccNotifBatBar.style.width = "98%";
                ccNotifBatBar.className = "bg-emerald-500 h-full transition-all duration-300";
            }
        }, 6000);
    }

    // Turret action
    function engageTurretTargetLock() {
        const barrel = document.getElementById('turret-gun-barrel');
        const tabBarrel = document.getElementById('tab-turret-gun-barrel');
        if (barrel) barrel.style.transform = "rotate(90deg)";
        if (tabBarrel) tabBarrel.style.transform = "rotate(90deg)";
        const panAngleEl = document.getElementById('turret-pan-angle');
        const tabPanAngleEl = document.getElementById('tab-turret-pan-angle');
        if (panAngleEl) panAngleEl.innerText = "90°";
        if (tabPanAngleEl) tabPanAngleEl.innerText = "90°";

        playToneBeep(1400, 0.15);
        showTacticalToast(`🎯 Target Sektor ${selectedSector} terkunci (Lock-On 90°)`);

        fetch('/api/gateway/receive', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                sensor_type: 'turret',
                sensor_name: 'DEFENSIVE_TURRET',
                protocol: 'WebSocket',
                timestamp: Date.now() / 1000,
                data: { target_lock: true, pan_angle: 90, sector: selectedSector, firing_authorized: false }
            })
        }).then(() => pollSystemState()).catch(() => {});

        if (isTestRunning && currentTaskId === 1) completeUsabilityTask(1);
    }

    function fireTurretManual() {
        playToneBeep(220, 0.08);
        setTimeout(() => playToneBeep(200, 0.08), 80);
        setTimeout(() => playToneBeep(180, 0.08), 160);
        showTacticalToast(`💥 Tembakan Beruntun Dilesatkan ke Sektor ${selectedSector}!`);

        const barrels = [document.getElementById('turret-gun-barrel'), document.getElementById('tab-turret-gun-barrel')];
        barrels.forEach(b => {
            if (b) b.classList.add('bg-rose-500');
        });
        setTimeout(() => barrels.forEach(b => {
            if (b) b.classList.remove('bg-rose-500');
        }), 150);

        fetch('/api/gateway/receive', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                sensor_type: 'turret',
                sensor_name: 'DEFENSIVE_TURRET',
                protocol: 'WebSocket',
                timestamp: Date.now() / 1000,
                data: { target_lock: true, pan_angle: 90, sector: selectedSector, firing_authorized: true, burst_rounds: 10 }
            })
        }).then(() => pollSystemState()).catch(() => {});
    }

    // CCTV AI Camera grid simulator drawing loop
    function initCameraAIFeed() {
        const canvas = document.getElementById('camera-feed-canvas');
        const canvasTab = document.getElementById('tab-camera-canvas-1');
        const canvases = [canvas, canvasTab];

        canvases.forEach(c => {
            if (!c) return;
            const ctx = c.getContext('2d');
            c.width = 320;
            c.height = 180;

            function draw() {
                ctx.fillStyle = '#1e293b';
                ctx.fillRect(0, 0, c.width, c.height);

                ctx.strokeStyle = 'rgba(255, 255, 255, 0.04)';
                ctx.lineWidth = 1;
                for (let x = 0; x < c.width; x += 15) {
                    ctx.beginPath(); ctx.moveTo(x, 0); ctx.lineTo(x, c.height); ctx.stroke();
                }
                for (let y = 0; y < c.height; y += 15) {
                    ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(c.width, y); ctx.stroke();
                }

                if (Math.random() > 0.4) {
                    ctx.strokeStyle = '#ef4444';
                    ctx.lineWidth = 1.5;
                    ctx.strokeRect(60, 30, 90, 110);
                    ctx.fillStyle = '#ef4444';
                    ctx.font = 'bold 8px sans-serif';
                    ctx.fillText('TARGET: PENYUSUP (94%)', 60, 24);

                    const status = document.getElementById('ai-detection-status');
                    const conf = document.getElementById('ai-confidence');
                    if (status) {
                        status.innerText = "ANCAMAN TERDETEKSI";
                        status.className = "font-bold text-xs text-rose-600";
                    }
                    if (conf) conf.innerText = "94.2 %";
                } else {
                    const status = document.getElementById('ai-detection-status');
                    const conf = document.getElementById('ai-confidence');
                    if (status) {
                        status.innerText = "PERIMETER AMAN";
                        status.className = "font-bold text-xs text-emerald-600";
                    }
                    if (conf) conf.innerText = "0.0 %";
                }
                setTimeout(() => requestAnimationFrame(draw), 1000);
            }
            draw();
        });
    }

    // Seismic wave drawing loop
    function initPerimeterWave() {
        const canvas = document.getElementById('perimeter-vibe-canvas');
        const canvasTab = document.getElementById('tab-perimeter-vibe-canvas');
        const canvases = [canvas, canvasTab];

        canvases.forEach(c => {
            if (!c) return;
            const ctx = c.getContext('2d');
            c.width = 280;
            c.height = 100;

            let points = Array(50).fill(10);

            function draw() {
                ctx.fillStyle = '#f8fafc';
                ctx.fillRect(0, 0, c.width, c.height);

                ctx.strokeStyle = 'rgba(148, 163, 184, 0.1)';
                ctx.lineWidth = 0.5;
                for (let y = 0; y < c.height; y += 20) {
                    ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(c.width, y); ctx.stroke();
                }

                let vibe = Math.random() * 5 + 4;
                const statusCheckEl = document.getElementById('perimeter-status');
                if (statusCheckEl && statusCheckEl.innerText === 'ANCAMAN DI PAGAR') {
                    vibe = Math.random() * 50 + 40;
                }
                
                points.push(vibe);
                points.shift();

                ctx.strokeStyle = vibe > 30 ? '#f43f5e' : '#6366f1';
                ctx.lineWidth = 1.5;
                ctx.beginPath();
                for (let i = 0; i < points.length; i++) {
                    const x = (c.width / (points.length - 1)) * i;
                    const y = c.height - (points[i] * (c.height / 100));
                    if (i === 0) ctx.moveTo(x, y);
                    else ctx.lineTo(x, y);
                }
                ctx.stroke();

                const vibeVal = document.getElementById('perimeter-vibration-val');
                if (vibeVal) vibeVal.innerText = Math.round(vibe) + ' Hz';
                
                const statusText = vibe > 30 ? "ANCAMAN DI PAGAR" : "SECURE";
                const statusClass = vibe > 30 ? "font-bold text-rose-600 font-mono" : "font-bold text-emerald-600 font-mono";
                
                const statusEl = document.getElementById('perimeter-status');
                if (statusEl) {
                    statusEl.innerText = statusText;
                    statusEl.className = statusClass;
                }

                // Update Notification Widget
                const ccNotifStatus = document.getElementById('cc-notif-perimeter-status');
                const ccNotifDesc = document.getElementById('cc-notif-perimeter-desc');
                if (ccNotifStatus && ccNotifDesc) {
                    ccNotifStatus.innerText = vibe > 30 ? "WARNING" : "SECURE";
                    ccNotifStatus.className = vibe > 30 ? "text-rose-600 font-bold animate-pulse" : "text-emerald-600 font-bold";
                    ccNotifDesc.innerText = vibe > 30 ? "Peringatan: Getaran abnormal terdeteksi pada pagar!" : "Pagar perimeter stabil dari gangguan fisik.";
                }

                if (document.getElementById('tab-perimeter-status')) {
                    document.getElementById('tab-perimeter-status').innerText = statusText;
                    document.getElementById('tab-perimeter-status').className = statusClass;
                    document.getElementById('tab-perimeter-val').innerText = Math.round(vibe) + ' Hz';
                }

                setTimeout(() => requestAnimationFrame(draw), 150);
            }
            draw();
        });
    }

    // Clicks / Usability Measurement
    function handlePageClick(event) {
        if (!isTestRunning) return;
        let targetEl = event.target;
        let isValid = false;

        if (currentTaskId === 1) {
            // Allows: Sector Alpha, Penyusup button, nav-turret, lock-target, tab-turret-dial
            if (targetEl.id === 'sector-alpha' || targetEl.closest('#btn-trigger-intruder') || targetEl.closest('#nav-turret') || targetEl.closest('#btn-lock-target') || targetEl.closest('#evaluator-container-widget') || targetEl.closest('#tab-content-evaluator')) isValid = true;
        } else if (currentTaskId === 2) {
            if (targetEl.closest('#btn-toggle-ddos') || targetEl.closest('#evaluator-container-widget') || targetEl.closest('#tab-content-evaluator')) isValid = true;
        } else if (currentTaskId === 3) {
            if (targetEl.id === 'sector-beta' || targetEl.closest('#btn-deploy-drone') || targetEl.closest('#btn-deploy-drone-tab') || targetEl.closest('#evaluator-container-widget') || targetEl.closest('#tab-content-evaluator')) isValid = true;
        }

        if (!isValid) {
            testMisclicks++;
            const misclicksEl = document.getElementById('eval-misclicks');
            if (misclicksEl) misclicksEl.innerText = testMisclicks;
            targetEl.classList.add('ring-2', 'ring-rose-500');
            setTimeout(() => targetEl.classList.remove('ring-2', 'ring-rose-500'), 500);
        }
    }

    function startUsabilityTask(taskId) {
        if (isTestRunning) {
            clearInterval(testTimerInterval);
            resetAllGuideHighlights();
        }

        isTestRunning = true;
        currentTaskId = taskId;
        testStartTime = Date.now();
        testMisclicks = 0;
        const misclicksEl = document.getElementById('eval-misclicks');
        if (misclicksEl) misclicksEl.innerText = 0;

        const badgePrefixes = ['task-badge-', 'tab-task-badge-'];
        badgePrefixes.forEach(pre => {
            const el = document.getElementById(pre + taskId);
            if (el) {
                el.innerText = "ONGOING";
                el.className = "text-[8px] bg-amber-50 text-amber-700 border border-amber-200 px-1.5 py-0.5 rounded-full font-mono font-bold animate-pulse";
            }
        });

        testTimerInterval = setInterval(() => {
            const elapsed = (Date.now() - testStartTime) / 1000;
            const watchEl = document.getElementById('eval-stopwatch');
            const tabWatchEl = document.getElementById('tab-eval-stopwatch');
            if (watchEl) watchEl.innerText = elapsed.toFixed(2) + 's';
            if (tabWatchEl) tabWatchEl.innerText = 'Waktu: ' + elapsed.toFixed(2) + 's';
            if (elapsed >= 30) failUsabilityTask(taskId);
        }, 50);

        if (taskId === 1) {
            const sectorAlpha = document.getElementById('sector-alpha');
            const intruderBtn = document.getElementById('btn-trigger-intruder');
            if (sectorAlpha) sectorAlpha.classList.add('guide-highlight');
            if (intruderBtn) intruderBtn.classList.add('guide-highlight');
        } else if (taskId === 2) {
            const ddosBtn = document.getElementById('btn-toggle-ddos');
            if (ddosBtn) ddosBtn.classList.add('guide-highlight');
        } else if (taskId === 3) {
            const sectorBeta = document.getElementById('sector-beta');
            if (sectorBeta) sectorBeta.classList.add('guide-highlight');
        }
    }

    function completeUsabilityTask(taskId) {
        clearInterval(testTimerInterval);
        isTestRunning = false;
        resetAllGuideHighlights();

        const elapsed = (Date.now() - testStartTime) / 1000;
        const badgePrefixes = ['task-badge-', 'tab-task-badge-'];
        
        badgePrefixes.forEach(pre => {
            const badge = document.getElementById(pre + taskId);
            if (badge) {
                badge.innerText = `PASSED (${elapsed.toFixed(1)}s)`;
                badge.className = "text-[8px] bg-emerald-50 text-emerald-700 border border-emerald-200 px-1.5 py-0.5 rounded-full font-mono font-bold";
            }
        });

        const taskWidget = document.getElementById('task-widget-' + taskId);
        const tabWidget = document.getElementById('tab-task-widget-' + taskId);
        if (taskWidget) taskWidget.className = "border border-emerald-200 bg-emerald-50/30 rounded-xl p-3 shadow-sm";
        if (tabWidget) tabWidget.className = "border border-emerald-200 bg-emerald-50/30 rounded-xl p-4 shadow-sm";
    }

    function failUsabilityTask(taskId) {
        clearInterval(testTimerInterval);
        isTestRunning = false;
        resetAllGuideHighlights();

        const badgePrefixes = ['task-badge-', 'tab-task-badge-'];
        badgePrefixes.forEach(pre => {
            const badge = document.getElementById(pre + taskId);
            if (badge) {
                badge.innerText = "TIMEOUT";
                badge.className = "text-[8px] bg-rose-50 text-rose-700 border border-rose-200 px-1.5 py-0.5 rounded-full font-mono font-bold";
            }
        });

        const taskWidget = document.getElementById('task-widget-' + taskId);
        const tabWidget = document.getElementById('tab-task-widget-' + taskId);
        if (taskWidget) taskWidget.className = "border border-rose-200 bg-rose-50/30 rounded-xl p-3 shadow-sm";
        if (tabWidget) tabWidget.className = "border border-rose-200 bg-rose-50/30 rounded-xl p-4 shadow-sm";
    }

    function resetAllGuideHighlights() {
        const ids = ['sector-alpha', 'btn-trigger-intruder', 'btn-toggle-ddos', 'sector-beta', 'btn-deploy-drone', 'btn-deploy-drone-tab', 'btn-lock-target', 'nav-turret'];
        ids.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.remove('guide-highlight');
        });
    }

    // Update BE-FE Latency UI
    function updateLatencyUI(rtt) {
        const valEl = document.getElementById('diagnostic-latency-val');
        const badgeEl = document.getElementById('diagnostic-latency-badge');
        const barContainer = document.getElementById('latency-bar-container');
        const trendAvgEl = document.getElementById('latency-trend-avg');
        
        if (!valEl) return;
        
        valEl.innerText = rtt + ' ms';
        
        // Add to history
        latencyHistory.push(rtt);
        if (latencyHistory.length > 10) {
            latencyHistory.shift();
        }
        
        // Calculate average
        const avg = Math.round(latencyHistory.reduce((a, b) => a + b, 0) / latencyHistory.length);
        if (trendAvgEl) trendAvgEl.innerText = `Avg: ${avg} ms`;
        
        // Classify network state
        let status = 'EXCELLENT';
        let badgeClass = 'text-[8px] font-bold px-2 py-0.5 rounded-full font-mono uppercase bg-emerald-50 text-emerald-600 border border-emerald-200/50';
        if (rtt >= 50 && rtt < 150) {
            status = 'NORMAL';
            badgeClass = 'text-[8px] font-bold px-2 py-0.5 rounded-full font-mono uppercase bg-blue-50 text-blue-600 border border-blue-200/50';
        } else if (rtt >= 150 && rtt < 300) {
            status = 'SLOW';
            badgeClass = 'text-[8px] font-bold px-2 py-0.5 rounded-full font-mono uppercase bg-amber-50 text-amber-600 border border-amber-200/50 animate-pulse';
        } else if (rtt >= 300) {
            status = 'DELAY';
            badgeClass = 'text-[8px] font-bold px-2 py-0.5 rounded-full font-mono uppercase bg-rose-50 text-rose-600 border border-rose-200/50 animate-pulse';
        }
        
        if (badgeEl) {
            badgeEl.innerText = status;
            badgeEl.className = badgeClass;
        }
        
        // Render sparkline bars
        if (barContainer) {
            barContainer.innerHTML = '';
            for (let i = 0; i < 10; i++) {
                const val = latencyHistory[i] !== undefined ? latencyHistory[i] : 2;
                let heightPct = Math.min(100, Math.max(8, (val / 120) * 100)); // scaling for high responsiveness
                let colorClass = 'bg-indigo-500';
                if (val >= 50 && val < 150) colorClass = 'bg-blue-500';
                else if (val >= 150 && val < 300) colorClass = 'bg-amber-500';
                else if (val >= 300) colorClass = 'bg-rose-500';
                
                const span = document.createElement('span');
                span.className = `w-[8%] ${colorClass} rounded-sm transition-all duration-300`;
                span.style.height = `${heightPct}%`;
                span.title = latencyHistory[i] !== undefined ? `${val} ms` : 'Waiting...';
                barContainer.appendChild(span);
            }
        }
    }

    // Handle offline UI for latency and sensor health
    function updateLatencyUIOffline() {
        const valEl = document.getElementById('diagnostic-latency-val');
        const badgeEl = document.getElementById('diagnostic-latency-badge');
        const trendAvgEl = document.getElementById('latency-trend-avg');
        const barContainer = document.getElementById('latency-bar-container');
        
        if (valEl) valEl.innerText = 'OFFLINE';
        if (badgeEl) {
            badgeEl.innerText = 'DOWN';
            badgeEl.className = 'text-[8px] font-bold px-2 py-0.5 rounded-full font-mono uppercase bg-rose-600 text-white animate-pulse';
        }
        if (trendAvgEl) trendAvgEl.innerText = 'Avg: -- ms';
        
        if (barContainer) {
            barContainer.innerHTML = '';
            for (let i = 0; i < 10; i++) {
                const span = document.createElement('span');
                span.className = 'w-[8%] bg-rose-600 h-0.5 rounded-sm animate-pulse';
                barContainer.appendChild(span);
            }
        }
        
        // Mark all 5 sensors as offline
        const sensors = ['camera', 'drone', 'perimeter', 'iot', 'turret'];
        sensors.forEach(type => {
            const ledPing = document.getElementById(`led-ping-${type}`);
            const ledState = document.getElementById(`led-state-${type}`);
            const detailEl = document.getElementById(`status-detail-${type}`);
            const badgeEl = document.getElementById(`badge-state-${type}`);
            
            if (ledPing) ledPing.className = 'animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75';
            if (ledState) ledState.className = 'relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-600';
            if (detailEl) detailEl.innerText = 'Sistem offline / terputus.';
            if (badgeEl) {
                badgeEl.innerText = 'OFFLINE';
                badgeEl.className = 'text-[8px] font-bold font-mono px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 uppercase';
            }
        });
    }

    // Update Sensor Health state
    function updateSensorHealthUI(health) {
        if (!health) return;
        
        Object.entries(health).forEach(([type, details]) => {
            const ledPing = document.getElementById(`led-ping-${type}`);
            const ledState = document.getElementById(`led-state-${type}`);
            const detailEl = document.getElementById(`status-detail-${type}`);
            const badgeEl = document.getElementById(`badge-state-${type}`);
            
            if (!ledState) return;
            
            const isOk = details.status === 'Normal';
            
            if (isOk) {
                if (ledPing) ledPing.className = 'animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75';
                if (ledState) ledState.className = 'relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500';
                if (detailEl) detailEl.innerText = `${details.sensor_name}: Kondisi Baik`;
                if (badgeEl) {
                    badgeEl.innerText = 'NORMAL';
                    badgeEl.className = 'text-[8px] font-bold font-mono px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-600 uppercase';
                }
            } else {
                const colorClass = details.status_text === 'FAULT' ? 'rose' : 'amber';
                if (ledPing) ledPing.className = `animate-ping absolute inline-flex h-full w-full rounded-full bg-${colorClass}-400 opacity-75`;
                if (ledState) ledState.className = `relative inline-flex rounded-full h-2.5 w-2.5 bg-${colorClass}-500`;
                if (detailEl) detailEl.innerText = `${details.sensor_name}: ${details.error_message}`;
                if (badgeEl) {
                    badgeEl.innerText = details.status_text;
                    badgeEl.className = `text-[8px] font-bold font-mono px-2 py-0.5 rounded-full bg-${colorClass}-50 text-${colorClass}-600 uppercase animate-pulse`;
                }
            }
        });
    }

    // Render sensor anomalies on console terminal
    function updateSensorErrorLogsUI(errorLogs) {
        const consoleEl = document.getElementById('diagnostic-error-log-console');
        const countEl = document.getElementById('diagnostic-error-count');
        
        if (!consoleEl) return;
        
        if (!errorLogs || errorLogs.length === 0) {
            consoleEl.innerHTML = `<p class="text-slate-500 italic">Tidak ada warning/error sensor tercatat.</p>`;
            if (countEl) {
                countEl.innerText = '0 WARNINGS';
                countEl.className = 'text-[8px] bg-slate-800 text-slate-400 font-bold px-1.5 py-0.5 rounded border border-slate-700/50 font-mono';
            }
            return;
        }
        
        if (countEl) {
            countEl.innerText = `${errorLogs.length} WARNINGS`;
            countEl.className = 'text-[8px] bg-rose-500/20 text-rose-400 font-bold px-1.5 py-0.5 rounded border border-rose-500/10 font-mono';
        }
        
        consoleEl.innerHTML = errorLogs.map(log => {
            const time = new Date(log.created_at).toLocaleTimeString();
            const colorClass = log.sensor_type === 'turret' ? 'text-rose-400' : 'text-amber-400';
            return `
                <div class="border-b border-slate-850 pb-1 flex gap-2">
                    <span class="text-slate-500 shrink-0">[${time}]</span>
                    <span class="${colorClass} font-bold shrink-0">${log.sensor_name}</span>
                    <span class="text-slate-350">${log.message}</span>
                </div>
            `;
        }).join('');
    }


</script>
