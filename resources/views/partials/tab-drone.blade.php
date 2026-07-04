<div id="tab-content-drone" class="tab-pane hidden space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Telemetry Data (Span 6) -->
        <div class="lg:col-span-6">
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Status Telemetri Penerbangan</h3>
                <div class="grid grid-cols-2 gap-4 text-center font-mono">
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-3">
                        <span class="block text-[9px] text-slate-400 font-semibold">Tinggi Terbang</span>
                        <span id="tab-drone-alt" class="text-xl font-bold text-slate-800">0 m</span>
                    </div>
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-3">
                        <span class="block text-[9px] text-slate-400 font-semibold">Baterai Multirotor</span>
                        <span id="tab-drone-bat" class="text-xl font-bold text-indigo-600">98%</span>
                    </div>
                </div>

                <div class="mt-4 space-y-2.5 text-xs">
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-400">Kecepatan Angin:</span>
                        <span class="font-bold">12 km/h</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-400">Sinyal Telemetri:</span>
                        <span class="text-emerald-600 font-bold">EXCELLENT (98%)</span>
                    </div>
                    <div class="flex justify-between py-1">
                        <span class="text-slate-400">Koordinat Sektor saat ini:</span>
                        <span id="tab-drone-sec" class="font-bold text-indigo-600">Hangar (Standby)</span>
                    </div>
                </div>

                <button id="btn-deploy-drone-tab" onclick="deployDroneAction()" class="w-full mt-5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2.5 rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Luncurkan Drone Patroli</span>
                </button>
            </div>
        </div>

        <!-- Flight Map View (Span 6) -->
        <div class="lg:col-span-6">
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Jalur Terbang Drone (Track Visual)</h3>
                <div class="bg-white border border-slate-200 rounded-xl aspect-video relative overflow-hidden flex items-center justify-center shadow-inner">
                    <div class="text-slate-400 text-xs text-center p-6">
                        <i class="fa-solid fa-satellite text-3xl mb-2 text-indigo-500 animate-pulse"></i>
                        <p class="font-medium text-slate-700">Drone Terkunci di Koordinat Utama.</p>
                        <p class="text-[10px] text-slate-400 mt-1">Garis arah visual akan aktif jika drone diluncurkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
