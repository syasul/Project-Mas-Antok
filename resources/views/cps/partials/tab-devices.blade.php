<!-- TAB 6: STATUS NODE GERBANG & PERANGKAT CPS SENSOR -->
<div id="tab-content-devices" class="hidden space-y-6">
    
    <!-- Header Banner -->
    <section class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-700 flex items-center justify-center font-bold text-lg shrink-0">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900 uppercase font-mono tracking-tight">STATUS NODE GERBANG &amp; SENSOR EDGE CPS</h2>
                    <p class="text-xs text-slate-500">Monitoring status koneksi perangkat keras kamera edge, FPS, dan latensi jaringan</p>
                </div>
            </div>

            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-300 self-start sm:self-auto font-mono">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <span>4 / 4 EDGE NODES ONLINE</span>
            </span>
        </div>
    </section>

    <!-- 4 Nodes Grid -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        <!-- Node 1: Gate Utama -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-3">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">NODE ID: CAM_GATE_UTAMA_01</span>
                    <h3 class="font-extrabold text-sm text-slate-900 mt-1">Gate Utama (Pos 1 Poltekad)</h3>
                    <p class="text-[11px] text-slate-500">Pintu gerbang masuk utama personel &amp; tamu</p>
                </div>
                <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-xs"></span>
            </div>

            <div class="grid grid-cols-3 gap-2 text-center py-2 bg-slate-50 rounded-lg border border-slate-100 font-mono text-xs">
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">STATUS:</span>
                    <span class="font-bold text-emerald-600">ONLINE</span>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">FPS:</span>
                    <span class="font-bold text-slate-800">30 FPS</span>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">LATENSI:</span>
                    <span class="font-bold text-slate-800">18.4 ms</span>
                </div>
            </div>

            <div class="pt-1 flex items-center justify-between">
                <span class="text-[11px] text-slate-400">Protokol: WebSocket / REST Ingest</span>
                <button onclick="triggerSimulatedScan('verified')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-camera text-[10px]"></i>
                    <span>Uji Scan Node</span>
                </button>
            </div>
        </div>

        <!-- Node 2: Pos Barat -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-3">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">NODE ID: CAM_POS_BARAT_02</span>
                    <h3 class="font-extrabold text-sm text-slate-900 mt-1">Pos Penjagaan Sektor Barat</h3>
                    <p class="text-[11px] text-slate-500">Akses jalur logistik &amp; perimeter barat</p>
                </div>
                <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-xs"></span>
            </div>

            <div class="grid grid-cols-3 gap-2 text-center py-2 bg-slate-50 rounded-lg border border-slate-100 font-mono text-xs">
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">STATUS:</span>
                    <span class="font-bold text-emerald-600">ONLINE</span>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">FPS:</span>
                    <span class="font-bold text-slate-800">30 FPS</span>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">LATENSI:</span>
                    <span class="font-bold text-slate-800">22.8 ms</span>
                </div>
            </div>

            <div class="pt-1 flex items-center justify-between">
                <span class="text-[11px] text-slate-400">Protokol: WebSocket / REST Ingest</span>
                <button onclick="triggerSimulatedScan('failed')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-camera text-[10px]"></i>
                    <span>Uji Anomali Node</span>
                </button>
            </div>
        </div>

        <!-- Node 3: Barak Taruna -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-3">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">NODE ID: CAM_BARAK_TARUNA_03</span>
                    <h3 class="font-extrabold text-sm text-slate-900 mt-1">Pintu Masuk Barak Taruna</h3>
                    <p class="text-[11px] text-slate-500">Pemeriksaan biometrik presensi apel taruna</p>
                </div>
                <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-xs"></span>
            </div>

            <div class="grid grid-cols-3 gap-2 text-center py-2 bg-slate-50 rounded-lg border border-slate-100 font-mono text-xs">
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">STATUS:</span>
                    <span class="font-bold text-emerald-600">ONLINE</span>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">FPS:</span>
                    <span class="font-bold text-slate-800">30 FPS</span>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">LATENSI:</span>
                    <span class="font-bold text-slate-800">19.2 ms</span>
                </div>
            </div>

            <div class="pt-1 flex items-center justify-between">
                <span class="text-[11px] text-slate-400">Protokol: WebSocket / REST Ingest</span>
                <button onclick="triggerSimulatedScan('verified')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-camera text-[10px]"></i>
                    <span>Uji Scan Node</span>
                </button>
            </div>
        </div>

        <!-- Node 4: Lab Cyber -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs space-y-3">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">NODE ID: CAM_LAB_KOMPUTER_04</span>
                    <h3 class="font-extrabold text-sm text-slate-900 mt-1">Gedung Lab Cyber &amp; Rekayasa</h3>
                    <p class="text-[11px] text-slate-500">Otorisasi akses ruang server &amp; lab riset</p>
                </div>
                <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-xs"></span>
            </div>

            <div class="grid grid-cols-3 gap-2 text-center py-2 bg-slate-50 rounded-lg border border-slate-100 font-mono text-xs">
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">STATUS:</span>
                    <span class="font-bold text-emerald-600">ONLINE</span>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">FPS:</span>
                    <span class="font-bold text-slate-800">30 FPS</span>
                </div>
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">LATENSI:</span>
                    <span class="font-bold text-slate-800">16.5 ms</span>
                </div>
            </div>

            <div class="pt-1 flex items-center justify-between">
                <span class="text-[11px] text-slate-400">Protokol: WebSocket / REST Ingest</span>
                <button onclick="triggerSimulatedScan('verified')" class="px-3 py-1.5 rounded-lg text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-colors flex items-center gap-1">
                    <i class="fa-solid fa-camera text-[10px]"></i>
                    <span>Uji Scan Node</span>
                </button>
            </div>
        </div>

    </section>

</div>
