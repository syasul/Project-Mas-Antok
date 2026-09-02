<!-- MAIN HEADER TOPBAR -->
<header class="h-16 bg-white border-b border-slate-200 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 shadow-2xs">
    
    <!-- Left: Hamburger Toggle & Breadcrumbs -->
    <div class="flex items-center gap-3">
        
        <!-- Mobile Sidebar Hamburger -->
        <button onclick="toggleSidebarMobile()" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors">
            <i class="fa-solid fa-bars text-sm"></i>
        </button>

        <!-- Current View Title & Subtitle -->
        <div>
            <div class="flex items-center gap-2">
                <h2 id="topbar-view-title" class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight uppercase font-mono">
                    PUSAT AUTENTIKASI REAL-TIME
                </h2>
                <span id="topbar-view-badge" class="hidden sm:inline-block bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-200 font-mono">
                    HCD VIEW
                </span>
            </div>
            <p id="topbar-view-desc" class="text-[11px] text-slate-500 font-medium hidden md:block">
                Pemantauan telemetri wajah biometrik gerbang Poltekad secara real-time
            </p>
        </div>

    </div>

    <!-- Right: WebSocket Live Status, Simulation Shortcuts, Clock -->
    <div class="flex items-center gap-3">
        
        <!-- Simulation Control Dropdown / Quick Buttons -->
        <div class="hidden sm:flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200 text-xs font-semibold">
            <span class="text-[10px] font-mono text-slate-500 px-2 font-bold uppercase">Simulasi:</span>
            <button onclick="triggerSimulatedScan('verified')" title="Simulasi Taruna Terverifikasi" class="px-2 py-1 rounded-lg bg-white text-emerald-700 hover:bg-emerald-50 border border-slate-200/80 shadow-2xs transition-all flex items-center gap-1 text-[11px]">
                <i class="fa-solid fa-user-check text-emerald-600"></i>
                <span>Lolos</span>
            </button>
            <button onclick="triggerSimulatedScan('failed')" title="Simulasi Wajah Tidak Dikenal" class="px-2 py-1 rounded-lg bg-white text-rose-700 hover:bg-rose-50 border border-slate-200/80 shadow-2xs transition-all flex items-center gap-1 text-[11px]">
                <i class="fa-solid fa-user-xmark text-rose-600"></i>
                <span>Gagal</span>
            </button>
            <button onclick="triggerSimulatedScan('anomaly')" title="Simulasi Upaya Penyamaran/Spoofing" class="px-2 py-1 rounded-lg bg-white text-amber-700 hover:bg-amber-50 border border-slate-200/80 shadow-2xs transition-all flex items-center gap-1 text-[11px]">
                <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                <span>Anomali</span>
            </button>
        </div>

        <!-- WebSocket Status Indicator -->
        <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-xl">
            <span class="relative flex h-2 w-2">
                <span id="ws-pulse" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span id="ws-dot" class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
            </span>
            <span id="ws-status-text" class="text-[11px] font-bold text-emerald-700 uppercase font-mono tracking-tight hidden sm:inline">WEBSOCKET LIVE</span>
            <span class="text-slate-300 hidden sm:inline">|</span>
            <span id="header-latency-val" class="text-[11px] font-mono font-bold text-slate-800">
                {{ $stats['avg_latency'] ?? 24.5 }} ms
            </span>
        </div>

        <!-- Live Server Clock -->
        <div class="hidden xl:flex items-center gap-1.5 text-xs font-mono text-slate-600 bg-slate-50 border border-slate-200 px-2.5 py-1.5 rounded-xl">
            <i class="fa-regular fa-clock text-slate-400"></i>
            <span id="live-server-clock">--:--:--</span>
        </div>

    </div>

</header>
