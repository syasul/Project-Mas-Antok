<!-- TAB 1: COMMAND CENTER OVERVIEW -->
<div id="tab-content-overview" class="tab-pane space-y-6">
    
    <!-- CORE SYSTEM KPI SUMMARY -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Active Defense State -->
        <div class="glass-card rounded-2xl p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                <i class="fa-solid fa-shield-halved text-lg"></i>
            </div>
            <div>
                <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider">Status Keamanan</span>
                <span class="block text-xs font-bold text-slate-800 uppercase" id="kpi-status-state">SIAGA 1 (SECURE)</span>
            </div>
        </div>

        <!-- Card 2: Connected Sensors -->
        <div class="glass-card rounded-2xl p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                <i class="fa-solid fa-network-wired text-lg"></i>
            </div>
            <div>
                <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider">Node Sensor Aktif</span>
                <span class="block text-xs font-bold text-slate-800">5 / 5 Node Terhubung</span>
            </div>
        </div>

        <!-- Card 3: Network Throughput -->
        <div class="glass-card rounded-2xl p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm shrink-0">
                <i class="fa-solid fa-wifi text-lg"></i>
            </div>
            <div>
                <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider">Tingkat Trafik Data</span>
                <span class="block text-xs font-bold text-slate-800" id="kpi-traffic-rate">1,245 Pkts / Detik</span>
            </div>
        </div>

        <!-- Card 4: Threat Alerts -->
        <div class="glass-card rounded-2xl p-4 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-sm shrink-0" id="kpi-threat-icon-bg">
                <i class="fa-solid fa-triangle-exclamation text-lg text-indigo-600" id="kpi-threat-icon"></i>
            </div>
            <div>
                <span class="block text-[9px] text-slate-400 font-bold uppercase tracking-wider">Ancaman Aktif</span>
                <span class="block text-xs font-bold text-slate-800" id="kpi-threat-count">0 Terdeteksi</span>
            </div>
        </div>
    </div>

    <!-- SIMULATOR CONTROL & DIAGNOSTICS PANEL -->
    <div class="glass-card rounded-2xl p-5 grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
        <div>
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wide">Simulator Kontrol Utama</h4>
            <p class="text-[9px] text-slate-450 font-medium leading-relaxed">Uji skenario beban database dan serangan ddos secara lokal.</p>
        </div>
        
        <!-- Server State dropdown selector -->
        <div class="flex items-center bg-slate-50 border border-slate-200/80 rounded-xl px-3 py-2 gap-2.5 shadow-sm">
            <span class="text-[9px] text-slate-400 font-bold uppercase">Beban Server:</span>
            <select id="sim-server-state" onchange="changeServerState(this.value)" class="bg-transparent text-[11px] text-slate-700 font-bold font-mono focus:outline-none flex-1">
                <option value="normal">NORMAL (Online)</option>
                <option value="overload">DB OVERLOAD (&gt; 500ms)</option>
                <option value="down">SERVER DOWN (500)</option>
            </select>
        </div>

        <!-- DDoS simulation toggle button -->
        <button id="btn-toggle-ddos" onclick="toggleDdosSimulation()" class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-750 px-3 py-2 rounded-xl text-[11px] font-bold font-mono transition-all flex items-center justify-center gap-2 shadow-sm">
            <span id="ddos-status-pulse" class="w-2 h-2 rounded-full bg-slate-300"></span>
            <span>SIMULASI DDOS FLOOD</span>
        </button>

        <!-- Dynamic network throughput counters -->
        <div class="flex gap-2">
            <!-- Total Logs Count -->
            <div class="flex-1 bg-slate-50 border border-slate-200/70 rounded-xl p-2 text-center font-mono shadow-sm">
                <span class="block text-[8px] text-slate-400 font-bold uppercase">TOTAL LOGS</span>
                <span id="stat-total-logs" class="text-xs font-bold text-slate-850">0</span>
            </div>
            <!-- Average Latency -->
            <div class="flex-1 bg-slate-50 border border-slate-200/70 rounded-xl p-2 text-center font-mono shadow-sm">
                <span class="block text-[8px] text-slate-400 font-bold uppercase">AVG LATENCY</span>
                <span id="stat-latency" class="text-xs font-bold text-slate-850">0 ms</span>
            </div>
        </div>
    </div>

    <!-- MAIN MAP & CONTROL PANEL GRID -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Sektor Map Card (Span 8) -->
        <div class="lg:col-span-8">
            <section class="glass-card rounded-2xl p-5">
                <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-bold tracking-wider text-slate-400 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-indigo-500"></i>
                        Tampilan Tata Letak Sektor Poltekad (Klik untuk Simulasi Ancaman)
                    </h3>
                    <span class="text-[9px] bg-slate-100 text-slate-500 font-bold px-2.5 py-1 rounded-full uppercase tracking-wider" id="selected-sector-badge">TERPILIH: ALPHA</span>
                </div>

                <!-- SVG Area blueprint map -->
                <div class="bg-white border border-slate-200 rounded-xl relative overflow-hidden p-2 shadow-inner">
                    <svg viewBox="0 0 600 380" class="w-full h-auto text-slate-400 select-none">
                        <defs>
                            <pattern id="light-grid-cc" width="20" height="20" patternUnits="userSpaceOnUse">
                                <path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(148, 163, 184, 0.05)" stroke-width="1"/>
                            </pattern>
                        </defs>
                        <rect width="600" height="380" fill="url(#light-grid-cc)"/>

                        <!-- Boundaries -->
                        <rect x="15" y="15" width="570" height="350" rx="12" fill="none" stroke="#cbd5e1" stroke-dasharray="5,5" stroke-width="1.5"/>
                        <text x="30" y="32" fill="#94a3b8" font-family="monospace" font-size="9" font-weight="bold">LINE PERIMETER UTARA</text>
                        <text x="30" y="352" fill="#94a3b8" font-family="monospace" font-size="9" font-weight="bold">LINE PERIMETER SELATAN</text>

                        <!-- Radar Range Lines -->
                        <circle cx="300" cy="190" r="140" fill="none" stroke="rgba(99, 102, 241, 0.05)" stroke-width="1"/>
                        <circle cx="300" cy="190" r="90" fill="none" stroke="rgba(99, 102, 241, 0.03)" stroke-width="1"/>

                        <!-- Sector Alpha -->
                        <path id="sector-alpha" onclick="selectSector('Alpha')" d="M 25,25 L 260,25 L 260,165 L 25,165 Z" class="cursor-pointer transition-all duration-200 hover:fill-indigo-50/40 fill-slate-50/40 stroke-slate-200" stroke-width="1" />
                        
                        <!-- Sector Beta -->
                        <path id="sector-beta" onclick="selectSector('Beta')" d="M 340,215 L 575,215 L 575,355 L 340,355 Z" class="cursor-pointer transition-all duration-200 hover:fill-indigo-50/40 fill-slate-50/40 stroke-slate-200" stroke-width="1" />
                        
                        <!-- Drone Hangar -->
                        <path id="sector-drone" onclick="selectSector('Drone Hangar')" d="M 25,215 L 260,215 L 260,355 L 25,355 Z" class="cursor-pointer transition-all duration-200 hover:fill-indigo-50/40 fill-slate-50/40 stroke-slate-200" stroke-width="1" />

                        <!-- Turret Tower -->
                        <path id="sector-turret" onclick="selectSector('Turret Tower')" d="M 340,25 L 575,25 L 575,165 L 340,165 Z" class="cursor-pointer transition-all duration-200 hover:fill-indigo-50/40 fill-slate-50/40 stroke-slate-200" stroke-width="1" />

                        <!-- HQ -->
                        <path id="sector-hq" onclick="selectSector('HQ')" d="M 275,145 L 325,145 L 325,235 L 275,235 Z" class="cursor-pointer transition-all duration-200 hover:fill-indigo-50/40 fill-slate-50/40 stroke-slate-200" stroke-width="1" />

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

                <!-- Threat triggers & Sector details briefing split -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-12 gap-4">
                    
                    <!-- Sector Detailed Briefing (Span 7) -->
                    <div class="md:col-span-7 p-4 border border-slate-200/80 bg-slate-50/50 rounded-2xl text-xs">
                        <h4 class="font-bold text-slate-500 uppercase tracking-wider mb-2.5 flex items-center gap-2">
                            <i class="fa-solid fa-circle-info text-indigo-500"></i>
                            Detail Status Sektor: <span id="detail-sector-name" class="text-indigo-600 font-extrabold">ALPHA</span>
                        </h4>
                        <div class="grid grid-cols-2 gap-x-4 gap-y-2 font-medium text-[11px] text-slate-600">
                            <div class="flex justify-between py-1 border-b border-slate-200/50">
                                <span class="text-slate-400">Tingkat Risiko:</span>
                                <span id="detail-sector-risk" class="text-emerald-600 font-bold font-mono">LOW</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-200/50">
                                <span class="text-slate-400">Kamera AI:</span>
                                <span id="detail-sector-cam" class="font-semibold text-slate-750">CAM_101 (Aktif)</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-200/50">
                                <span class="text-slate-400">Perimeter:</span>
                                <span id="detail-sector-perim" class="font-semibold text-slate-750">PERIM_S2 (Aktif)</span>
                            </div>
                            <div class="flex justify-between py-1 border-b border-slate-200/50">
                                <span class="text-slate-400">Unit Turret:</span>
                                <span id="detail-sector-turret" class="font-semibold text-slate-755">TURRET_1 (Standby)</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-2.5 italic" id="detail-sector-desc">Sektor Alpha mencakup pertahanan perimeter luar bagian utara, dilengkapi kamera AI klasifikasi target dan sensor getaran fiber-optik.</p>
                    </div>

                    <!-- Threat Simulator triggers (Span 5) -->
                    <div class="md:col-span-5 p-4 border border-slate-200/80 bg-slate-50/50 rounded-2xl flex flex-col justify-between">
                        <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5">Simulasikan Ancaman Sektor</h4>
                        <div class="grid grid-cols-3 gap-2">
                            <button id="btn-trigger-intruder" onclick="triggerSimulatedThreat('intruder')" class="bg-white hover:bg-indigo-50/20 border border-slate-200 text-slate-750 p-2 rounded-xl text-xs flex flex-col items-center justify-center gap-1 transition-all shadow-sm">
                                <i class="fa-solid fa-person-rifle text-slate-500 text-[11px]"></i>
                                <span class="font-bold text-[9px]">Penyusup</span>
                            </button>
                            <button onclick="triggerSimulatedThreat('breach')" class="bg-white hover:bg-indigo-50/20 border border-slate-200 text-slate-750 p-2 rounded-xl text-xs flex flex-col items-center justify-center gap-1 transition-all shadow-sm">
                                <i class="fa-solid fa-border-top-left text-slate-500 text-[11px]"></i>
                                <span class="font-bold text-[9px]">Perimeter</span>
                            </button>
                            <button onclick="triggerSimulatedThreat('uav')" class="bg-white hover:bg-indigo-50/20 border border-slate-200 text-slate-750 p-2 rounded-xl text-xs flex flex-col items-center justify-center gap-1 transition-all shadow-sm">
                                <i class="fa-solid fa-plane-slash text-slate-500 text-[11px]"></i>
                                <span class="font-bold text-[9px]">UAV Liar</span>
                            </button>
                            <button onclick="triggerSimulatedThreat('iot_attack')" class="bg-white hover:bg-indigo-50/20 border border-slate-200 text-slate-755 p-2 rounded-xl text-xs flex flex-col items-center justify-center gap-1 transition-all shadow-sm">
                                <i class="fa-solid fa-microchip text-slate-500 text-[11px]"></i>
                                <span class="font-bold text-[9px]">Anomali</span>
                            </button>
                            <button onclick="triggerSimulatedThreat('turret_fail')" class="bg-white hover:bg-indigo-50/20 border border-slate-200 text-slate-750 p-2 rounded-xl text-xs flex flex-col items-center justify-center gap-1 transition-all shadow-sm col-span-2">
                                <i class="fa-solid fa-triangle-exclamation text-slate-500 text-[11px]"></i>
                                <span class="font-bold text-[9px]">Turret Rusak</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Metrics & Alerts Panel (Span 4) -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Operational Metrics Card -->
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Metrik Status Operasional</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-500">Beban CPU Kontroler:</span>
                            <span id="metric-cpu-val" class="font-mono text-slate-750">18%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            <div id="metric-cpu-bar" class="bg-indigo-500 h-full rounded-full transition-all duration-300" style="width: 18%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-slate-500">Penggunaan Memori:</span>
                            <span id="metric-ram-val" class="font-mono text-slate-750">4.2 / 16 GB</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            <div id="metric-ram-bar" class="bg-indigo-500 h-full rounded-full transition-all duration-300" style="width: 26%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Alerts Card -->
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Status Ancaman Aktif</h3>
                <div class="space-y-3 max-h-56 overflow-y-auto" id="dashboard-active-alerts-list">
                    <p class="text-xs text-slate-400 text-center py-4">Tidak ada ancaman aktif saat ini (Secure).</p>
                </div>
            </div>

            <!-- Status Notifications & Warnings Widget -->
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Notifikasi Status Aset Keamanan</h3>
                <div class="space-y-3">
                    <!-- Battery Warning -->
                    <div class="flex items-center gap-3 p-2 bg-slate-50 border border-slate-100 rounded-xl" id="cc-notif-drone-battery">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600"><i class="fa-solid fa-battery-three-quarters text-xs"></i></div>
                        <div class="flex-1">
                            <div class="flex justify-between text-[10px] font-bold text-slate-700">
                                <span>Baterai Drone Patroli</span>
                                <span id="cc-notif-drone-battery-val">98%</span>
                            </div>
                            <div class="w-full bg-slate-200 h-1 rounded-full mt-1 overflow-hidden">
                                <div id="cc-notif-drone-battery-bar" class="bg-emerald-500 h-full transition-all duration-300" style="width: 98%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Turret Ammunition Capacity -->
                    <div class="flex items-center gap-3 p-2 bg-slate-50 border border-slate-100 rounded-xl">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600"><i class="fa-solid fa-gun text-xs"></i></div>
                        <div class="flex-1">
                            <div class="flex justify-between text-[10px] font-bold text-slate-700">
                                <span>Amunisi Turret Utama</span>
                                <span id="cc-notif-turret-ammo-val">350 / 500 Rds</span>
                            </div>
                            <div class="w-full bg-slate-200 h-1 rounded-full mt-1 overflow-hidden">
                                <div id="cc-notif-turret-ammo-bar" class="bg-indigo-500 h-full" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Perimeter Wire Stability -->
                    <div class="flex items-center gap-3 p-2 bg-slate-50 border border-slate-100 rounded-xl">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600"><i class="fa-solid fa-border-top-left text-xs"></i></div>
                        <div class="flex-1">
                            <div class="flex justify-between text-[10px] font-bold text-slate-700">
                                <span>Getaran Pagar Sektor</span>
                                <span id="cc-notif-perimeter-status" class="text-emerald-600 font-bold">SECURE</span>
                            </div>
                            <p class="text-[8px] text-slate-400 font-medium mt-0.5" id="cc-notif-perimeter-desc">Pagar perimeter stabil dari gangguan fisik.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Raw Logs activity flow -->
    <div class="grid grid-cols-1 gap-6">
        <section class="glass-card rounded-2xl p-5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Aliran Log Gateway Terintegrasi</h3>
            <div class="overflow-x-auto w-full max-h-56">
                <table class="w-full text-xs font-mono text-left">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-bold">
                            <th class="py-2.5 px-2">TIMESTAMP</th>
                            <th class="py-2.5 px-2">PROTOKOL</th>
                            <th class="py-2.5 px-2">ASSET SENSOR</th>
                            <th class="py-2.5 px-2">LATENSI</th>
                            <th class="py-2.5 px-2">TELEMETRI DATA</th>
                        </tr>
                    </thead>
                    <tbody id="cc-raw-log-tbody" class="divide-y divide-slate-50">
                        <tr>
                            <td colspan="5" class="py-4 text-center text-slate-400">Menghubungkan sensor gateway...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
