<!-- CPS DASHBOARD TOPBAR (HCD - Light Mode) -->
<header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Left: Brand & Institution Identity -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white shadow-xs shrink-0">
                    <i class="fa-solid fa-shield-halved text-emerald-400 text-lg"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-sm sm:text-base text-slate-900 tracking-tight">CPS AUTHENTICATION</span>
                        <span class="bg-slate-100 text-slate-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-slate-200 uppercase tracking-wider font-mono">POLTEKAD</span>
                    </div>
                    <p class="text-[11px] text-slate-500 font-medium hidden sm:block">Sistem Verifikasi Biometrik Real-Time Berbasis HCD & WebSocket</p>
                </div>
            </div>

            <!-- Middle: Live WebSocket Status & Latency Instrument -->
            <div class="hidden md:flex items-center gap-4 bg-slate-50 px-3.5 py-1.5 rounded-xl border border-slate-200">
                <!-- WebSocket Status Indicator -->
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span id="ws-pulse" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span id="ws-dot" class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span id="ws-status-text" class="text-xs font-bold text-emerald-700 uppercase tracking-wider font-mono">WEBSOCKET LIVE</span>
                </div>

                <div class="h-4 w-px bg-slate-200"></div>

                <!-- Live Latency Display (<100ms Target) -->
                <div class="flex items-center gap-1.5 text-xs text-slate-600 font-mono">
                    <i class="fa-solid fa-bolt text-amber-500 text-[11px]"></i>
                    <span>Latensi:</span>
                    <span id="header-latency-val" class="font-bold text-slate-900">{{ $stats['avg_latency'] ?? 24.5 }} ms</span>
                    <span class="text-[9px] text-emerald-600 bg-emerald-50 px-1.5 py-0.2 rounded border border-emerald-200 font-bold">&lt;100ms OK</span>
                </div>

                <div class="h-4 w-px bg-slate-200"></div>

                <!-- Clock -->
                <div class="flex items-center gap-1.5 text-xs font-mono text-slate-600">
                    <i class="fa-regular fa-clock text-slate-400"></i>
                    <span id="live-server-clock">--:--:--</span>
                </div>
            </div>

            <!-- Right: Navigation Links & Operator Profile -->
            <div class="flex items-center gap-2 sm:gap-3">
                
                <!-- Quick Navigation to SUS & Usability Analytics -->
                <a href="{{ route('usability.sus') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 transition-colors shadow-xs">
                    <i class="fa-solid fa-clipboard-check text-indigo-600"></i>
                    <span class="hidden sm:inline">Kuesioner SUS</span>
                    <span class="sm:hidden">SUS</span>
                </a>

                <a href="{{ route('usability.results') }}" class="hidden lg:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 border border-slate-200 hover:bg-slate-200 transition-colors">
                    <i class="fa-solid fa-chart-pie text-slate-500"></i>
                    <span>Hasil Usability</span>
                </a>

                <!-- User Profile & Logout -->
                <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-bold text-slate-900 leading-tight">{{ auth()->user()->name ?? 'Letnan Dua Antok' }}</div>
                        <div class="text-[10px] font-semibold text-emerald-600 uppercase font-mono">OPERATOR JAGA</div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" title="Logout" class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                            <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</header>
