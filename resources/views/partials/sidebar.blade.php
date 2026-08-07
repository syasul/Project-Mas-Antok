<!-- MOBILE SIDEBAR BACKDROP OVERLAY -->
<div id="sidebar-backdrop" onclick="toggleMobileSidebar()" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-30 lg:hidden transition-all duration-300"></div>

<!-- SIDEBAR -->
<aside id="sidebar-nav" class="fixed inset-y-0 left-0 w-64 bg-white border-r border-slate-200/80 flex flex-col justify-between h-screen z-40 shadow-sm shrink-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <div>
        <!-- Brand Logo Area -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-soft-indigo">
                    <i class="fa-solid fa-shield-halved text-lg"></i>
                </div>
                <div>
                    <h1 class="text-sm font-bold tracking-tight text-slate-900">POLTEKAD KODIKLATAD</h1>
                    <p class="text-[9px] text-slate-400 font-mono uppercase tracking-wide">SECURE CONTROL CENTER</p>
                </div>
            </div>
            <!-- Mobile Close Button -->
            <button onclick="toggleMobileSidebar()" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <!-- Operator Profile -->
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs uppercase font-mono">
                OP
            </div>
            <div>
                <p class="text-xs font-bold text-slate-800">Operator Command</p>
                <p class="text-[9px] text-emerald-600 flex items-center gap-1 font-semibold">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    ONLINE - SECTOR MAIN
                </p>
            </div>
        </div>

        <!-- Sidebar Menus Navigation -->
        <nav class="p-4 space-y-1 overflow-y-auto max-h-[calc(100vh-170px)]">
            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider px-3 mb-2">Main Panel</p>
            
            <button onclick="switchTab('overview')" id="nav-overview" class="w-full flex items-center gap-3 px-3 py-2 text-xs font-semibold rounded-xl text-indigo-600 bg-indigo-50 transition-all">
                <i class="fa-solid fa-desktop w-4"></i>
                <span>Pusat Komando (Peta)</span>
            </button>

            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider px-3 mt-4 mb-2">Subsistem Pertahanan</p>

            <button onclick="switchTab('camera')" id="nav-camera" class="w-full flex items-center gap-3 px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition-all">
                <i class="fa-solid fa-video w-4"></i>
                <span>Kamera Kecerdasan AI</span>
            </button>

            <button onclick="switchTab('drone')" id="nav-drone" class="w-full flex items-center gap-3 px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition-all">
                <i class="fa-solid fa-helicopter w-4"></i>
                <span>Drone Patroli Telemetri</span>
            </button>

            <button onclick="switchTab('perimeter')" id="nav-perimeter" class="w-full flex items-center gap-3 px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition-all">
                <i class="fa-solid fa-wave-square w-4"></i>
                <span>Sensor Getaran Pagar</span>
            </button>

            <button onclick="switchTab('iot')" id="nav-iot" class="w-full flex items-center gap-3 px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition-all">
                <i class="fa-solid fa-cloud-arrow-down w-4"></i>
                <span>Gateway Keamanan IoT</span>
            </button>

            <button onclick="switchTab('turret')" id="nav-turret" class="w-full flex items-center gap-3 px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition-all">
                <i class="fa-solid fa-crosshairs w-4"></i>
                <span>Unit Turret Defensif</span>
            </button>

            <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider px-3 mt-4 mb-2">Sistem Keputusan & Evaluasi</p>

            <button onclick="switchTab('decision')" id="nav-decision" class="w-full flex items-center gap-3 px-3 py-2 text-xs font-semibold rounded-xl text-slate-600 hover:text-indigo-600 hover:bg-slate-50 transition-all">
                <i class="fa-solid fa-brain w-4"></i>
                <span>Decision Log & Rule</span>
            </button>


        </nav>
    </div>

    <!-- Footer status indicators -->
    <div class="p-4 border-t border-slate-100 text-[10px] text-slate-400 font-mono space-y-1 bg-slate-50/20">
        <p class="flex items-center justify-between">
            <span>Versi:</span>
            <span class="font-bold text-slate-600">v1.0.0</span>
        </p>
        <p class="flex items-center justify-between">
            <span>Sistem:</span>
            <span id="aside-system-status" class="font-bold text-emerald-600 uppercase">ONLINE</span>
        </p>
    </div>
</aside>
