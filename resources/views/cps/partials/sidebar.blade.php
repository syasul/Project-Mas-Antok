<!-- SIDEBAR NAVIGATION (Enterprise Poltekad CPS Layout) -->
<aside id="cps-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 border-r border-slate-800 shadow-xl">
    
    <!-- Sidebar Header: Brand & Institution -->
    <div class="h-16 px-4 flex items-center justify-between border-b border-slate-800 shrink-0 bg-slate-950/40">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shrink-0">
                <i class="fa-solid fa-shield-halved text-base"></i>
            </div>
            <div class="overflow-hidden">
                <h1 class="text-xs font-black tracking-wider text-white uppercase font-mono truncate">CPS AUTHENTICATION</h1>
                <p class="text-[10px] text-slate-400 font-mono tracking-tight">POLTEKAD KODIKLATAD</p>
            </div>
        </div>

        <!-- Mobile Close Button -->
        <button onclick="toggleSidebarMobile()" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
            <i class="fa-solid fa-xmark text-sm"></i>
        </button>
    </div>

    <!-- Navigation Menu Items -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto text-xs font-semibold">
        
        <div class="px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">
            Menu Operasional
        </div>

        <!-- 1. Live Authentication (Primary Focal Point) -->
        <button onclick="switchTab('live')" id="nav-item-live" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-medium">
            <i class="fa-solid fa-camera-viewfinder text-sm w-4 text-center"></i>
            <span class="truncate">Live Autentikasi</span>
            <span class="ml-auto w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
        </button>

        <!-- 2. Verification History Logs -->
        <button onclick="switchTab('history')" id="nav-item-history" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all font-medium">
            <i class="fa-solid fa-clock-rotate-left text-sm w-4 text-center text-slate-400"></i>
            <span class="truncate">Riwayat &amp; Log Data</span>
            <span class="ml-auto text-[10px] font-mono px-1.5 py-0.2 rounded bg-slate-800 text-slate-400" id="sidebar-total-badge">{{ $stats['total'] ?? 0 }}</span>
        </button>

        <!-- 3. Edge Gates & CPS Devices -->
        <button onclick="switchTab('devices')" id="nav-item-devices" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all font-medium">
            <i class="fa-solid fa-network-wired text-sm w-4 text-center text-slate-400"></i>
            <span class="truncate">Node Gerbang &amp; Sensor</span>
            <span class="ml-auto text-[10px] font-mono text-emerald-400">4 Online</span>
        </button>

        <div class="pt-4 px-3 pb-2 text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">
            Evaluasi HCD &amp; Riset (SUS)
        </div>

        <!-- 4. Usability Task Stopwatch (TCT) -->
        <button onclick="switchTab('tct')" id="nav-item-tct" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all font-medium">
            <i class="fa-solid fa-stopwatch text-sm w-4 text-center text-amber-400"></i>
            <span class="truncate">Uji Tugas (TCT Timer)</span>
        </button>

        <!-- 5. SUS Questionnaire -->
        <button onclick="switchTab('sus')" id="nav-item-sus" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all font-medium">
            <i class="fa-solid fa-clipboard-check text-sm w-4 text-center text-indigo-400"></i>
            <span class="truncate">Kuesioner SUS</span>
            <span class="ml-auto text-[9px] font-mono px-1.5 py-0.2 rounded bg-indigo-950 text-indigo-300 border border-indigo-800 font-bold">&gt;75 Target</span>
        </button>

        <!-- 6. Usability Analytics Summary -->
        <button onclick="switchTab('analytics')" id="nav-item-analytics" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800/60 transition-all font-medium">
            <i class="fa-solid fa-chart-pie text-sm w-4 text-center text-teal-400"></i>
            <span class="truncate">Analisis Hasil Riset</span>
        </button>

    </nav>

    <!-- Sidebar Footer: Operator Info & Logout -->
    <div class="p-3 border-t border-slate-800 bg-slate-950/60 shrink-0">
        <div class="p-2.5 rounded-xl bg-slate-900 border border-slate-800 flex items-center justify-between gap-2">
            <div class="flex items-center gap-2.5 min-w-0">
                <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shrink-0">
                    AN
                </div>
                <div class="truncate">
                    <div class="text-xs font-bold text-white truncate leading-tight">{{ auth()->user()->name ?? 'Letnan Dua Antok' }}</div>
                    <div class="text-[10px] text-emerald-400 font-mono">Perwira Jaga CPS</div>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="shrink-0">
                @csrf
                <button type="submit" title="Logout dari Sesi" class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-slate-800 transition-colors">
                    <i class="fa-solid fa-power-off text-xs"></i>
                </button>
            </form>
        </div>
    </div>

</aside>

<!-- Backdrop overlay on mobile -->
<div id="sidebar-backdrop" onclick="toggleSidebarMobile()" class="hidden fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-xs lg:hidden transition-opacity"></div>
