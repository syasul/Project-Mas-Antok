<!-- TAB 1: COMMAND CENTER OVERVIEW -->
<div id="tab-content-overview" class="tab-pane space-y-6">
    
    <!-- CORE SYSTEM KPI SUMMARY -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Active Defense State -->
        <div class="glass-card rounded-2xl p-4 flex items-center gap-4 border border-slate-200/80 bg-white/70 shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm shrink-0 border border-indigo-100/50">
                <i class="fa-solid fa-shield-halved text-lg"></i>
            </div>
            <div>
                <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider">Status Keamanan</span>
                <span class="block text-xs font-bold text-slate-800 uppercase font-mono tracking-tight" id="kpi-status-state">SIAGA 1 (SECURE)</span>
            </div>
        </div>

        <!-- Card 2: Connected Sensors -->
        <div class="glass-card rounded-2xl p-4 flex items-center gap-4 border border-slate-200/80 bg-white/70 shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm shrink-0 border border-emerald-100/50">
                <i class="fa-solid fa-network-wired text-lg"></i>
            </div>
            <div>
                <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider">Node Sensor Aktif</span>
                <span class="block text-xs font-bold text-slate-800 font-mono tracking-tight">5 / 5 Node Terhubung</span>
            </div>
        </div>

        <!-- Card 3: Network Throughput -->
        <div class="glass-card rounded-2xl p-4 flex items-center gap-4 border border-slate-200/80 bg-white/70 shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-sky-50 flex items-center justify-center text-sky-600 shadow-sm shrink-0 border border-sky-100/50">
                <i class="fa-solid fa-wifi text-lg"></i>
            </div>
            <div>
                <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider">Tingkat Trafik Data</span>
                <span class="block text-xs font-bold text-slate-800 font-mono tracking-tight" id="kpi-traffic-rate">1,245 Pkts / Detik</span>
            </div>
        </div>

        <!-- Card 4: Threat Alerts -->
        <div class="glass-card rounded-2xl p-4 flex items-center gap-4 border border-slate-200/80 bg-white/70 shadow-sm">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-650 shadow-sm shrink-0 border border-indigo-100/50" id="kpi-threat-icon-bg">
                <i class="fa-solid fa-triangle-exclamation text-lg text-indigo-655" id="kpi-threat-icon"></i>
            </div>
            <div>
                <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider">Ancaman Aktif</span>
                <span class="block text-xs font-bold text-slate-800 font-mono tracking-tight" id="kpi-threat-count">0 Terdeteksi</span>
            </div>
        </div>
    </div>

    <!-- MAIN GRID LAYOUT -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- LEFT COLUMN (Span 8) - Map, Command Deck, and Logs -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Map and Briefing Card -->
            <section class="glass-card rounded-2xl p-5 border border-slate-200/80 bg-white/70 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
                    <div class="flex items-center gap-3">
                        <h3 class="text-xs font-bold tracking-wider text-slate-500 uppercase flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-indigo-500"></i>
                            Peta Sektor Poltekad (Pusat Kendali Operasi)
                        </h3>
                        <span class="text-[9px] bg-indigo-50 text-indigo-700 font-bold px-2.5 py-1 rounded-full uppercase tracking-wider border border-indigo-100/50 font-mono" id="selected-sector-badge">TERPILIH: ALPHA</span>
                    </div>

                    <!-- Map Mode Toggle: Blueprint SVG vs Real GIS Leaflet -->
                    <div class="inline-flex bg-slate-100 p-0.5 rounded-lg border border-slate-200 text-[10px] font-semibold">
                        <button id="btn-map-mode-svg" onclick="switchMapMode('svg')" class="px-2.5 py-1 rounded-md bg-white text-indigo-600 shadow-sm transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-draw-polygon"></i>
                            <span>Blueprint Taktis</span>
                        </button>
                        <button id="btn-map-mode-gis" onclick="switchMapMode('gis')" class="px-2.5 py-1 rounded-md text-slate-500 hover:text-slate-800 transition-all flex items-center gap-1.5">
                            <i class="fa-solid fa-satellite"></i>
                            <span>GIS Satelit Riil</span>
                        </button>
                    </div>
                </div>

                <!-- 1. SVG Blueprint Map -->
                <div id="map-blueprint-container" class="bg-white border border-slate-150 rounded-xl relative overflow-hidden p-2 shadow-inner">
                    <svg viewBox="0 0 600 380" class="w-full h-auto text-slate-400 select-none">
                        <defs>
                            <pattern id="light-grid-cc" width="20" height="20" patternUnits="userSpaceOnUse">
                                <path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(148, 163, 184, 0.04)" stroke-width="1"/>
                            </pattern>
                        </defs>
                        <rect width="600" height="380" fill="url(#light-grid-cc)"/>

                        <!-- Boundaries -->
                        <rect x="15" y="15" width="570" height="350" rx="12" fill="none" stroke="#e2e8f0" stroke-dasharray="5,5" stroke-width="1.5"/>
                        <text x="30" y="32" fill="#94a3b8" font-family="monospace" font-size="9" font-weight="bold">LINE PERIMETER UTARA</text>
                        <text x="30" y="352" fill="#94a3b8" font-family="monospace" font-size="9" font-weight="bold">LINE PERIMETER SELATAN</text>

                        <!-- Radar Range Lines -->
                        <circle cx="300" cy="190" r="140" fill="none" stroke="rgba(99, 102, 241, 0.04)" stroke-width="1"/>
                        <circle cx="300" cy="190" r="90" fill="none" stroke="rgba(99, 102, 241, 0.02)" stroke-width="1"/>

                        <!-- Sector Alpha -->
                        <path id="sector-alpha" onclick="selectSector('Alpha')" d="M 25,25 L 260,25 L 260,165 L 25,165 Z" class="cursor-pointer transition-all duration-200 hover:fill-indigo-50/30 fill-slate-50/20 stroke-slate-200" stroke-width="1" />
                        
                        <!-- Sector Beta -->
                        <path id="sector-beta" onclick="selectSector('Beta')" d="M 340,215 L 575,215 L 575,355 L 340,355 Z" class="cursor-pointer transition-all duration-200 hover:fill-indigo-50/30 fill-slate-50/20 stroke-slate-200" stroke-width="1" />
                        
                        <!-- Drone Hangar -->
                        <path id="sector-drone" onclick="selectSector('Drone Hangar')" d="M 25,215 L 260,215 L 260,355 L 25,355 Z" class="cursor-pointer transition-all duration-200 hover:fill-indigo-50/30 fill-slate-50/20 stroke-slate-200" stroke-width="1" />

                        <!-- Turret Tower -->
                        <path id="sector-turret" onclick="selectSector('Turret Tower')" d="M 340,25 L 575,25 L 575,165 L 340,165 Z" class="cursor-pointer transition-all duration-200 hover:fill-indigo-50/30 fill-slate-50/20 stroke-slate-200" stroke-width="1" />

                        <!-- HQ -->
                        <path id="sector-hq" onclick="selectSector('HQ')" d="M 275,145 L 325,145 L 325,235 L 275,235 Z" class="cursor-pointer transition-all duration-200 hover:fill-indigo-50/30 fill-slate-50/20 stroke-slate-200" stroke-width="1" />

                        <rect x="282" y="175" width="36" height="30" rx="4" fill="#6366f1" />
                        <text x="300" y="193" text-anchor="middle" fill="#ffffff" font-weight="bold" font-size="10">HQ</text>

                        <!-- Map Text labels -->
                        <text x="45" y="45" fill="#475569" font-weight="bold" font-size="11">SECTOR ALPHA</text>
                        <text x="45" y="58" fill="#94a3b8" font-size="8" font-family="monospace">POS PERIMETER UTARA</text>
                        <text x="360" y="235" fill="#475569" font-weight="bold" font-size="11">SECTOR BETA</text>
                        <text x="360" y="248" fill="#94a3b8" font-size="8" font-family="monospace">POS PERIMETER SELATAN</text>
                        <text x="45" y="235" fill="#475569" font-weight="bold" font-size="11">DRONE HANGAR</text>
                        <text x="360" y="45" fill="#475569" font-weight="bold" font-size="11">TURRET BATERAI</text>

                        <!-- Nodes -->
                        <circle cx="80" cy="110" r="5" fill="#10b981" id="map-node-camera" class="animate-pulse"/>
                        <text x="92" y="113" fill="#64748b" font-size="8" font-family="monospace">CAM_101</text>
                        <line x1="420" y1="310" x2="520" y2="310" stroke="#10b981" stroke-width="2.5" id="map-node-perimeter"/>
                        <text x="420" y="323" fill="#64748b" font-size="8" font-family="monospace">PERIM_S2</text>
                        <circle cx="450" cy="110" r="9" fill="none" stroke="#f59e0b" stroke-width="2" id="map-node-turret-ring"/>
                        <line x1="450" y1="110" x2="465" y2="100" stroke="#f59e0b" stroke-width="2.5" id="map-node-turret-barrel"/>
                        <text x="475" y="114" fill="#64748b" font-size="8" font-family="monospace">TURRET_1</text>
                        <polygon points="120,290 128,304 112,304" fill="#0ea5e9" id="map-node-drone"/>
                        <text x="136" y="301" fill="#64748b" font-size="8" font-family="monospace">DRONE_S4</text>
                    </svg>
                </div>

                <!-- 2. Interactive Satellite GIS Leaflet Map (Poltekad Kesatrian) -->
                <div id="map-gis-container" class="hidden bg-slate-900 border border-slate-700 rounded-xl relative overflow-hidden h-[380px] w-full shadow-inner">
                    <div id="leaflet-poltekad-map" class="w-full h-full z-0"></div>
                    <!-- Tactical overlay HUD on top of Leaflet map -->
                    <div class="absolute top-2 left-2 z-[400] bg-slate-900/80 backdrop-blur-md border border-slate-700/80 rounded-lg px-2.5 py-1.5 text-[10px] text-slate-200 font-mono flex items-center gap-2 shadow-lg">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>KOORDINAT POLTEKAD: <strong class="text-emerald-400">7°53'21.5"S 112°32'24.4"E</strong></span>
                    </div>
                    <div class="absolute bottom-2 right-2 z-[400] bg-slate-900/85 backdrop-blur-md border border-slate-700/80 rounded-lg px-2.5 py-1 text-[9px] text-slate-300 font-mono flex items-center gap-3 shadow-lg">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-400"></span> Sensor</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400"></span> Turret (300m)</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-cyan-400"></span> Drone Waypoint</span>
                    </div>
                </div>

                <!-- Sector Detailed Briefing -->
                <div class="mt-4 p-4 border border-slate-200/60 bg-slate-50/30 rounded-xl text-xs flex flex-col justify-between">
                    <div>
                        <h4 class="font-bold text-slate-500 uppercase tracking-wider mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-indigo-500"></i>
                            Detail Status Sektor: <span id="detail-sector-name" class="text-indigo-600 font-extrabold font-mono">ALPHA</span>
                        </h4>
                        <div class="grid grid-cols-2 gap-x-6 gap-y-1.5 font-medium text-[10px] text-slate-600">
                            <div class="flex justify-between py-0.5 border-b border-slate-200/50">
                                <span class="text-slate-400">Tingkat Risiko:</span>
                                <span id="detail-sector-risk" class="text-emerald-600 font-bold font-mono">LOW</span>
                            </div>
                            <div class="flex justify-between py-0.5 border-b border-slate-200/50">
                                <span class="text-slate-400">Kamera AI:</span>
                                <span id="detail-sector-cam" class="font-semibold text-slate-700">CAM_101 (Aktif)</span>
                            </div>
                            <div class="flex justify-between py-0.5 border-b border-slate-200/50">
                                <span class="text-slate-400">Perimeter:</span>
                                <span id="detail-sector-perim" class="font-semibold text-slate-700">PERIM_S2 (Aktif)</span>
                            </div>
                            <div class="flex justify-between py-0.5 border-b border-slate-200/50">
                                <span class="text-slate-400">Unit Turret:</span>
                                <span id="detail-sector-turret" class="font-semibold text-slate-700">TURRET_1 (Standby)</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2.5 italic leading-relaxed" id="detail-sector-desc">Sektor Alpha mencakup pertahanan perimeter luar bagian utara, dilengkapi kamera AI klasifikasi target dan sensor getaran fiber-optik.</p>
                </div>
            </section>

            <!-- Simulator Command Deck Card -->
            <section class="glass-card rounded-2xl p-5 border border-slate-200/80 bg-white/70 shadow-sm space-y-4">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-gamepad text-indigo-500"></i>
                    Pusat Simulasi Pengujian & Injeksi Ancaman (Command Deck)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                    
                    <!-- Column 1: Server Load Simulator -->
                    <div class="space-y-3.5">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">1. Beban Server & Telemetri</h4>
                        <div class="flex flex-col bg-slate-50 border border-slate-200/60 rounded-xl px-3 py-2 gap-1.5 shadow-sm">
                            <span class="text-[8px] text-slate-400 font-bold uppercase">Mode Kerja Server:</span>
                            <select id="sim-server-state" onchange="changeServerState(this.value)" class="bg-transparent text-[10px] text-slate-700 font-bold font-mono focus:outline-none w-full cursor-pointer">
                                <option value="normal">NORMAL (Online)</option>
                                <option value="overload">DB OVERLOAD (&gt;500ms)</option>
                                <option value="down">SERVER DOWN (500)</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-2 font-mono">
                            <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-2 text-center shadow-sm">
                                <span class="block text-[7px] text-slate-400 font-bold uppercase">Total Logs</span>
                                <span id="stat-total-logs" class="text-[11px] font-bold text-slate-800">0</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-2 text-center shadow-sm">
                                <span class="block text-[7px] text-slate-400 font-bold uppercase">Avg Latency</span>
                                <span id="stat-latency" class="text-[11px] font-bold text-slate-800">0 ms</span>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: DDoS Attack Simulator -->
                    <div class="space-y-3.5 h-full flex flex-col">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">2. Simulasi Jaringan</h4>
                        <div class="flex-1 flex flex-col justify-between">
                            <p class="text-[9px] text-slate-450 leading-relaxed mb-3">Simulasikan serangan banjiran DDoS packet rate tinggi ke gateway internal.</p>
                            <button id="btn-toggle-ddos" onclick="toggleDdosSimulation()" class="w-full bg-white hover:bg-slate-50 border border-slate-200 text-slate-750 px-3 py-2.5 rounded-xl text-[10px] font-bold font-mono transition-all flex items-center justify-center gap-2 shadow-sm">
                                <span id="ddos-status-pulse" class="w-2 h-2 rounded-full bg-slate-300"></span>
                                <span>SIMULASI DDOS FLOOD</span>
                            </button>
                        </div>
                    </div>

                    <!-- Column 3: Sektor Threat Injector -->
                    <div class="space-y-3.5">
                        <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">3. Injeksi Ancaman Sektor</h4>
                        <div class="grid grid-cols-2 gap-2">
                            <button id="btn-trigger-intruder" onclick="triggerSimulatedThreat('intruder')" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 p-2 rounded-xl text-xs flex flex-col items-center justify-center gap-1 transition-all shadow-sm">
                                <i class="fa-solid fa-person-rifle text-slate-500 text-xs"></i>
                                <span class="font-bold text-[8px] uppercase tracking-wide">Penyusup</span>
                            </button>
                            <button onclick="triggerSimulatedThreat('breach')" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 p-2 rounded-xl text-xs flex flex-col items-center justify-center gap-1 transition-all shadow-sm">
                                <i class="fa-solid fa-border-top-left text-slate-500 text-xs"></i>
                                <span class="font-bold text-[8px] uppercase tracking-wide">Pagar</span>
                            </button>
                            <button onclick="triggerSimulatedThreat('uav')" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 p-2 rounded-xl text-xs flex flex-col items-center justify-center gap-1 transition-all shadow-sm">
                                <i class="fa-solid fa-plane-slash text-slate-500 text-xs"></i>
                                <span class="font-bold text-[8px] uppercase tracking-wide">Drone Liar</span>
                            </button>
                            <button onclick="triggerSimulatedThreat('iot_attack')" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 p-2 rounded-xl text-xs flex flex-col items-center justify-center gap-1 transition-all shadow-sm">
                                <i class="fa-solid fa-microchip text-slate-500 text-xs"></i>
                                <span class="font-bold text-[8px] uppercase tracking-wide">Anomali</span>
                            </button>
                            <button onclick="triggerSimulatedThreat('turret_fail')" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 p-2 rounded-xl text-xs flex flex-col items-center justify-center gap-1 transition-all shadow-sm col-span-2">
                                <i class="fa-solid fa-triangle-exclamation text-slate-500 text-xs animate-pulse"></i>
                                <span class="font-bold text-[8px] uppercase tracking-wide">Turret Rusak</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Bottom Log Grid: Raw Logs Table -->
            <section class="glass-card rounded-2xl p-5 border border-slate-200/80 bg-white/70 shadow-sm flex flex-col h-[280px]">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 border-b border-slate-100 pb-2 shrink-0 flex items-center gap-1.5">
                    <i class="fa-solid fa-list-check text-indigo-500"></i> Aliran Log Gateway Terintegrasi
                </h3>
                <div class="overflow-y-auto w-full flex-1 pr-1 custom-scrollbar">
                    <table class="w-full text-[10px] font-mono text-left">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 font-bold">
                                <th class="py-2 px-1">TIMESTAMP</th>
                                <th class="py-2 px-1">PROTOKOL</th>
                                <th class="py-2 px-1">ASSET SENSOR</th>
                                <th class="py-2 px-1">LATENSI</th>
                                <th class="py-2 px-1">TELEMETRI DATA</th>
                            </tr>
                        </thead>
                        <tbody id="cc-raw-log-tbody" class="divide-y divide-slate-50 text-slate-650">
                            <tr>
                                <td colspan="5" class="py-4 text-center text-slate-400">Menghubungkan sensor gateway...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <!-- RIGHT COLUMN (Span 4) - System Metrics, Health, Alerts, & Terminal -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Unified System Health & Latency Card -->
            <div class="glass-card rounded-2xl p-5 border border-slate-200/80 bg-white/70 shadow-sm space-y-4">
                <div class="flex justify-between items-center border-b border-slate-100 pb-2">
                    <h3 class="text-xs font-bold text-slate-800 uppercase flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-nodes text-indigo-500"></i>
                        Koneksi & Beban Sistem (BE-FE)
                    </h3>
                    <span id="diagnostic-latency-badge" class="text-[8px] bg-slate-100 text-slate-500 font-bold px-2 py-0.5 rounded-full font-mono uppercase">WAITING</span>
                </div>
                
                <!-- CPU and RAM Usage bars -->
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-[10px] font-bold mb-1">
                            <span class="text-slate-500">Beban CPU Kontroler:</span>
                            <span id="metric-cpu-val" class="font-mono text-slate-700">18%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden border border-slate-200/20">
                            <div id="metric-cpu-bar" class="bg-indigo-500 h-full rounded-full transition-all duration-300" style="width: 18%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-[10px] font-bold mb-1">
                            <span class="text-slate-500">Penggunaan RAM:</span>
                            <span id="metric-ram-val" class="font-mono text-slate-700">4.2 / 16 GB</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden border border-slate-200/20">
                            <div id="metric-ram-bar" class="bg-indigo-500 h-full rounded-full transition-all duration-300" style="width: 26%"></div>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-slate-100 my-1"></div>

                <!-- Network Latency info -->
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500 text-[10px] font-bold">Latency Web (RTT):</span>
                        <span id="diagnostic-latency-val" class="text-base font-mono font-bold text-indigo-650 animate-pulse">-- ms</span>
                    </div>
                    
                    <!-- Latency Sparkline Graph -->
                    <div class="mt-2.5">
                        <div class="flex justify-between items-center text-[8px] text-slate-400 font-mono mb-1">
                            <span>Riwayat Ping (10 Polling Terakhir)</span>
                            <span id="latency-trend-avg">Avg: -- ms</span>
                        </div>
                        <div id="latency-bar-container" class="flex items-end gap-1.5 h-11 w-full bg-slate-50 border border-slate-150 rounded-xl p-2 overflow-hidden justify-between">
                            <span class="w-[8%] bg-slate-200 h-0.5 rounded-sm"></span>
                            <span class="w-[8%] bg-slate-200 h-0.5 rounded-sm"></span>
                            <span class="w-[8%] bg-slate-200 h-0.5 rounded-sm"></span>
                            <span class="w-[8%] bg-slate-200 h-0.5 rounded-sm"></span>
                            <span class="w-[8%] bg-slate-200 h-0.5 rounded-sm"></span>
                            <span class="w-[8%] bg-slate-200 h-0.5 rounded-sm"></span>
                            <span class="w-[8%] bg-slate-200 h-0.5 rounded-sm"></span>
                            <span class="w-[8%] bg-slate-200 h-0.5 rounded-sm"></span>
                            <span class="w-[8%] bg-slate-200 h-0.5 rounded-sm"></span>
                            <span class="w-[8%] bg-slate-200 h-0.5 rounded-sm"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Health Status 5 Sensors Card -->
            <div class="glass-card rounded-2xl p-5 border border-slate-200/80 bg-white/70 shadow-sm">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-heart-pulse text-indigo-500"></i>
                    Status Diagnostik Sensor
                </h3>
                
                <div class="space-y-2.5" id="diagnostic-sensor-health-list">
                    <!-- Sensor Camera -->
                    <div class="flex items-center justify-between p-2 bg-slate-50/40 border border-slate-100/80 rounded-xl">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span id="led-ping-camera" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75"></span>
                                <span id="led-state-camera" class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-500"></span>
                            </span>
                            <div>
                                <p class="text-[9px] font-bold text-slate-700 uppercase">Kamera Kecerdasan AI</p>
                                <p id="status-detail-camera" class="text-[8px] text-slate-400 font-mono">Menunggu data...</p>
                            </div>
                        </div>
                        <span id="badge-state-camera" class="text-[7px] font-bold font-mono px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-500 uppercase">UNKNOWN</span>
                    </div>

                    <!-- Sensor Drone -->
                    <div class="flex items-center justify-between p-2 bg-slate-50/40 border border-slate-100/80 rounded-xl">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span id="led-ping-drone" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75"></span>
                                <span id="led-state-drone" class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-500"></span>
                            </span>
                            <div>
                                <p class="text-[9px] font-bold text-slate-700 uppercase">Drone Patroli Telemetri</p>
                                <p id="status-detail-drone" class="text-[8px] text-slate-400 font-mono">Menunggu data...</p>
                            </div>
                        </div>
                        <span id="badge-state-drone" class="text-[7px] font-bold font-mono px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-500 uppercase">UNKNOWN</span>
                    </div>

                    <!-- Sensor Perimeter -->
                    <div class="flex items-center justify-between p-2 bg-slate-50/40 border border-slate-100/80 rounded-xl">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span id="led-ping-perimeter" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75"></span>
                                <span id="led-state-perimeter" class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-500"></span>
                            </span>
                            <div>
                                <p class="text-[9px] font-bold text-slate-700 uppercase">Sensor Getaran Pagar</p>
                                <p id="status-detail-perimeter" class="text-[8px] text-slate-400 font-mono">Menunggu data...</p>
                            </div>
                        </div>
                        <span id="badge-state-perimeter" class="text-[7px] font-bold font-mono px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-500 uppercase">UNKNOWN</span>
                    </div>

                    <!-- Sensor IoT -->
                    <div class="flex items-center justify-between p-2 bg-slate-50/40 border border-slate-100/80 rounded-xl">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2 w-2">
                                <span id="led-ping-iot" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75"></span>
                                <span id="led-state-iot" class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-500"></span>
                            </span>
                            <div>
                                <p class="text-[9px] font-bold text-slate-700 uppercase">Gateway Keamanan IoT</p>
                                <p id="status-detail-iot" class="text-[8px] text-slate-400 font-mono">Menunggu data...</p>
                            </div>
                        </div>
                        <span id="badge-state-iot" class="text-[7px] font-bold font-mono px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-500 uppercase">UNKNOWN</span>
                    </div>

                    <!-- Sensor Turret -->
                    <div class="flex items-center justify-between p-2 bg-slate-50/40 border border-slate-100/80 rounded-xl">
                        <div class="flex items-center gap-2">
                            <span class="relative flex h-2.5 w-2.5">
                                <span id="led-ping-turret" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75"></span>
                                <span id="led-state-turret" class="relative inline-flex rounded-full h-2.5 w-2.5 bg-slate-500"></span>
                            </span>
                            <div>
                                <p class="text-[9px] font-bold text-slate-700 uppercase">Unit Turret Defensif</p>
                                <p id="status-detail-turret" class="text-[8px] text-slate-400 font-mono">Menunggu data...</p>
                            </div>
                        </div>
                        <span id="badge-state-turret" class="text-[7px] font-bold font-mono px-1.5 py-0.5 rounded-full bg-slate-100 text-slate-500 uppercase">UNKNOWN</span>
                    </div>
                </div>
            </div>

            <!-- Terminal Error Log Box -->
            <section class="bg-slate-900 border border-slate-850 rounded-2xl p-5 shadow-inner text-slate-200 flex flex-col h-[280px]">
                <div class="flex justify-between items-center border-b border-slate-800 pb-2 mb-3 shrink-0">
                    <span class="text-[10px] font-bold uppercase tracking-wider font-mono text-slate-400 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                        <i class="fa-solid fa-terminal"></i> Terminal Eror Sensor
                    </span>
                    <span id="diagnostic-error-count" class="text-[8px] bg-rose-500/20 text-rose-400 font-bold px-1.5 py-0.5 rounded border border-rose-500/10 font-mono">0 WARNINGS</span>
                </div>
                <div id="diagnostic-error-log-console" class="font-mono text-[9px] space-y-2 overflow-y-auto leading-relaxed custom-scrollbar text-slate-400 flex-1 pr-1">
                    <p class="text-slate-500 italic">Memantau aliran data sensor...</p>
                </div>
            </section>

            <!-- Active Alerts Card -->
            <div class="glass-card rounded-2xl p-5 border border-slate-200/80 bg-white/70 shadow-sm">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2 flex items-center gap-1.5">
                    <i class="fa-solid fa-bell text-indigo-500"></i>
                    Status Ancaman Aktif
                </h3>
                <div class="space-y-3 max-h-48 overflow-y-auto" id="dashboard-active-alerts-list">
                    <p class="text-xs text-slate-400 text-center py-4">Tidak ada ancaman aktif saat ini (Secure).</p>
                </div>
            </div>
        </div>
    </div>
</div>
