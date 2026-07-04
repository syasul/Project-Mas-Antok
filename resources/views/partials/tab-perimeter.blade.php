<div id="tab-content-perimeter" class="tab-pane hidden space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Seismic Graph (Span 8) -->
        <div class="lg:col-span-8">
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Detektor Seismik Getaran Fiber Pagar</h3>
                
                <div class="relative bg-slate-50 border border-slate-200 rounded-xl overflow-hidden h-48 shadow-inner mb-4">
                    <canvas id="tab-perimeter-vibe-canvas" class="w-full h-full block"></canvas>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <span class="block text-slate-400 mb-1">Status Sensor:</span>
                        <span id="tab-perimeter-status" class="text-xs font-bold text-emerald-600">SECURE</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <span class="block text-slate-400 mb-1">Amplitudo Getar:</span>
                        <span id="tab-perimeter-val" class="text-xs font-bold text-slate-800">12 Hz</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Zones List (Span 4) -->
        <div class="lg:col-span-4">
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Daftar Zona Perimeter</h3>
                <div class="space-y-2.5">
                    <div class="flex justify-between items-center text-xs p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="font-bold text-slate-700">Zona 1 (North Gate)</span>
                        <span class="text-[9px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full uppercase">SECURE</span>
                    </div>
                    <div class="flex justify-between items-center text-xs p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="font-bold text-slate-700">Zona 2 (South Gate)</span>
                        <span class="text-[9px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full uppercase">SECURE</span>
                    </div>
                    <div class="flex justify-between items-center text-xs p-2.5 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="font-bold text-slate-700">Zona 3 (East Hangar)</span>
                        <span class="text-[9px] bg-emerald-100 text-emerald-700 font-bold px-2 py-0.5 rounded-full uppercase">SECURE</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
