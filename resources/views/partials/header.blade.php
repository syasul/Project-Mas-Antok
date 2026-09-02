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

    <!-- OPERATOR PROFILE & TACTICAL CONTROLS -->
    <div class="flex items-center gap-2 md:gap-4">
        <!-- Tactical Audio Siren Toggle -->
        <button id="btn-toggle-siren-audio" onclick="toggleSirenAudio()" title="Aktifkan/Matikan Sirene Audio Alarm Web" class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-xl border text-[11px] font-semibold transition-all bg-emerald-50 text-emerald-700 border-emerald-300 hover:bg-emerald-100 shadow-sm">
            <i id="siren-audio-icon" class="fa-solid fa-volume-high text-xs text-emerald-600"></i>
            <span id="siren-audio-text" class="hidden sm:inline">Sirene Audio: ON</span>
        </button>

        <!-- Dynamic ticking clock (hidden on micro-mobile screens) -->
        <div class="hidden lg:flex bg-slate-50 border border-slate-200/60 rounded-xl px-2.5 py-1.5 text-[10px] md:text-xs text-slate-600 font-mono items-center gap-2 shadow-sm">
            <i class="fa-solid fa-clock text-indigo-500"></i>
            <span id="live-clock">00:00:00</span>
        </div>

        <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

        <!-- Operator Details & RBAC Badge -->
        <div class="flex items-center gap-3">
            <div class="hidden md:block text-right">
                <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name ?? 'Letnan Dua Agung Nugroho' }}</p>
                <div class="flex items-center justify-end gap-1.5 mt-0.5">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[9px] px-1.5 py-0.5 rounded font-bold font-mono uppercase {{ Auth::user()->role_badge_class ?? 'bg-emerald-50 text-emerald-700 border border-emerald-200' }}">
                        {{ Auth::user()->rank_title ?? 'Perwira Jaga Taktis' }}
                    </span>
                </div>
            </div>
            
            <form action="{{ route('logout') }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="bg-slate-50 hover:bg-rose-50 hover:border-rose-200 border border-slate-200 rounded-xl px-3 py-1.5 text-slate-600 hover:text-rose-600 transition-all text-xs font-bold flex items-center gap-1.5 shadow-sm" title="Keluar dari Sesi Operasi">
                    <i class="fa-solid fa-power-off"></i>
                    <span class="hidden md:inline">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</header>
