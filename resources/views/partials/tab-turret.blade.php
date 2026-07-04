<div id="tab-content-turret" class="tab-pane hidden space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Turret Pointer Dial (Span 6) -->
        <div class="lg:col-span-6">
            <div class="glass-card rounded-2xl p-5 flex flex-col items-center">
                <h3 class="w-full text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2 text-left">Unit Rotasi Turret</h3>
                
                <div class="relative w-40 h-40 border border-slate-200 bg-white rounded-full flex items-center justify-center shadow-sm my-4" id="tab-turret-dial-container">
                    <div class="absolute w-36 h-36 border border-dashed border-indigo-200 rounded-full animate-spin" style="animation-duration: 20s;"></div>
                    <!-- Pointer -->
                    <div id="tab-turret-gun-barrel" class="absolute w-2 h-18 bg-indigo-500 origin-bottom bottom-18 transition-all duration-700" style="transform: rotate(45deg);"></div>
                    <div class="absolute w-5 h-5 bg-slate-800 rounded-full border border-white"></div>
                </div>

                <div class="grid grid-cols-2 gap-4 w-full text-center font-mono text-xs">
                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                        <span class="block text-[10px] text-slate-400 font-bold uppercase">PAN DEGREES</span>
                        <span id="tab-turret-pan-angle" class="font-bold text-slate-800">45°</span>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-2.5 border border-slate-100">
                        <span class="block text-[10px] text-slate-400 font-bold uppercase">MAGAZINE AMMO</span>
                        <span class="font-bold text-slate-800">350 / 500</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Firing authorization & overrides (Span 6) -->
        <div class="lg:col-span-6">
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Kontrol Otorisasi Penembakan</h3>
                
                <div class="space-y-4">
                    <div class="text-xs">
                        <span class="block text-slate-500 font-semibold mb-2">Mode Operasi Turret:</span>
                        <label class="flex items-center gap-2 mb-1.5 cursor-pointer">
                            <input type="radio" name="turret_mode" value="auto" checked class="accent-indigo-500">
                            <span class="font-medium">Otomatis (IF-THEN Engine Authorized)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="turret_mode" value="manual" class="accent-indigo-500">
                            <span class="font-medium">Manual Override (Operator Controlled)</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <button id="btn-lock-target" onclick="engageTurretTargetLock()" class="bg-white border border-slate-200 hover:bg-indigo-50/50 hover:border-indigo-500/30 text-indigo-600 text-xs font-bold py-2.5 rounded-xl transition-all shadow-sm">
                            LOCK TARGET SECTOR
                        </button>
                        <button onclick="fireTurretManual()" class="bg-white border border-slate-200 hover:bg-rose-50/50 hover:border-rose-500/30 text-rose-600 text-xs font-bold py-2.5 rounded-xl transition-all shadow-sm">
                            MANUAL BURST FIRE
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
