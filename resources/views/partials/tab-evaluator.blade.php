<div id="tab-content-evaluator" class="tab-pane hidden space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Guided Tasks Benchmark (Span 8) -->
        <div class="lg:col-span-8">
            <div class="glass-card rounded-2xl p-5">
                <div class="flex justify-between items-center mb-4 border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-bold text-slate-800 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-flask text-indigo-500"></i>
                        Skenario Tugas Pengguna Mandiri (UI/UX Benchmark)
                    </h3>
                    <div class="flex items-center gap-4 text-xs font-semibold">
                        <span class="text-slate-500">Misclicks: <span id="eval-misclicks" class="font-bold text-rose-500 font-mono">0</span></span>
                        <span class="text-[9px] bg-slate-100 text-slate-500 font-bold px-2 py-0.5 rounded-full font-mono uppercase" id="tab-eval-stopwatch">Waktu: 0.00s</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="border border-slate-100 bg-white rounded-xl p-4 shadow-sm" id="tab-task-widget-1">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-bold text-slate-700">Tugas 1: Tangani Penyusup Alpha</span>
                            <span id="tab-task-badge-1" class="text-[8px] bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded-full font-mono font-bold">READY</span>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium">Tindakan: Klik Sektor Alpha pada Peta, klik 'Penyusup', lalu klik 'LOCK TARGET' di panel Turret. (Target &lt; 30s)</p>
                        <button onclick="startUsabilityTask(1)" class="mt-2 text-[9px] bg-indigo-50 hover:bg-indigo-100 text-indigo-600 px-3 py-1.5 rounded-xl font-bold transition-all border border-indigo-100 shadow-sm">Mulai Tugas 1</button>
                    </div>

                    <div class="border border-slate-100 bg-white rounded-xl p-4 shadow-sm" id="tab-task-widget-2">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-bold text-slate-700">Tugas 2: Tangkal Serangan DDoS</span>
                            <span id="tab-task-badge-2" class="text-[8px] bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded-full font-mono font-bold">READY</span>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium">Tindakan: Aktifkan tombol 'SIMULASI DDOS' di kanan atas header, lalu tunggu hingga mitigasi memblokir serangan. (Target &lt; 30s)</p>
                        <button onclick="startUsabilityTask(2)" class="mt-2 text-[9px] bg-indigo-50 hover:bg-indigo-100 text-indigo-600 px-3 py-1.5 rounded-xl font-bold transition-all border border-indigo-100 shadow-sm">Mulai Tugas 2</button>
                    </div>

                    <div class="border border-slate-100 bg-white rounded-xl p-4 shadow-sm" id="tab-task-widget-3">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-bold text-slate-700">Tugas 3: Patroli Drone Sektor Beta</span>
                            <span id="tab-task-badge-3" class="text-[8px] bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded-full font-mono font-bold">READY</span>
                        </div>
                        <p class="text-[10px] text-slate-400 font-medium">Tindakan: Klik Sektor Beta pada peta, lalu luncurkan drone dengan menekan tombol 'DEPLOY DRONE PATROLI'. (Target &lt; 30s)</p>
                        <button onclick="startUsabilityTask(3)" class="mt-2 text-[9px] bg-indigo-50 hover:bg-indigo-100 text-indigo-600 px-3 py-1.5 rounded-xl font-bold transition-all border border-indigo-100 shadow-sm">Mulai Tugas 3</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUS Form Trigger (Span 4) -->
        <div class="lg:col-span-4">
            <div class="glass-card rounded-2xl p-5">
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Kuesioner SUS</h3>
                <p class="text-[10px] text-slate-400 font-medium mb-4 leading-relaxed">Setelah menguji seluruh alur tugas di atas, silakan isi kuesioner System Usability Scale (SUS) untuk mengukur tingkat kebergunaan antarmuka sistem secara kuantitatif.</p>
                <button onclick="openSusModal()" class="w-full bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold py-2.5 rounded-xl transition-all shadow-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-ranking-star"></i>
                    <span>Isi Kuesioner SUS</span>
                </button>
            </div>
        </div>
    </div>
</div>
