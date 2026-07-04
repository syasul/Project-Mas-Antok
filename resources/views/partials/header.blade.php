<header class="w-full px-4 md:px-8 py-4 border-b border-slate-200/80 bg-white/70 backdrop-blur-md flex items-center justify-between sticky top-0 z-10">
    <div class="flex items-center">
        <!-- Hamburger button for responsive mobile toggle -->
        <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 text-slate-500 hover:text-slate-700 hover:bg-slate-50 border border-slate-200 rounded-xl mr-3 transition-all flex items-center justify-center">
            <i class="fa-solid fa-bars text-sm"></i>
        </button>
        <div>
            <h2 id="page-title" class="text-xs md:text-sm font-bold text-slate-900 flex items-center gap-2 uppercase tracking-wide">
                PUSAT KOMANDO UTAMA
            </h2>
            <p id="page-subtitle" class="text-[9px] md:text-[10px] text-slate-400 uppercase font-mono tracking-wider">Pemantau Koordinat Peta & Telemetri Sensor Terpadu</p>
        </div>
    </div>

    <!-- OPERATOR PROFILE & LOGOUT -->
    <div class="flex items-center gap-4">
        <!-- Dynamic ticking clock (hidden on micro-mobile screens) -->
        <div class="hidden sm:flex bg-slate-50 border border-slate-200/60 rounded-xl px-2.5 py-1.5 text-[10px] md:text-xs text-slate-600 font-mono items-center gap-2 shadow-sm">
            <i class="fa-solid fa-clock text-indigo-500"></i>
            <span id="live-clock">00:00:00</span>
        </div>

        <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

        <!-- Operator Details -->
        <div class="flex items-center gap-3">
            <div class="hidden md:block text-right">
                <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name ?? 'Letnan Dua Syamsul' }}</p>
                <p class="text-[9px] text-slate-400 font-medium font-mono uppercase">Operator Command</p>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="bg-slate-50 hover:bg-rose-50 hover:border-rose-250 border border-slate-200 rounded-xl px-3 py-1.5 text-slate-600 hover:text-rose-600 transition-all text-xs font-bold flex items-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-power-off"></i>
                    <span class="hidden md:inline">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</header>
