<!-- TAB 3: USABILITY TESTING & TASK COMPLETION TIME (TCT) LAB -->
<div id="tab-content-tct" class="hidden space-y-6">
    
    <!-- Lab Header -->
    <section class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-700 flex items-center justify-center font-bold text-lg shrink-0">
                    <i class="fa-solid fa-stopwatch"></i>
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900 uppercase font-mono tracking-tight">LAB PENGUJIAN USABILITY &amp; TASK COMPLETION TIME</h2>
                    <p class="text-xs text-slate-500">Metrik Kuantitatif Deskriptif: Pengukuran Efisiensi Waktu &amp; Error Rate Operator Poltekad</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button onclick="switchTab('sus')" class="px-3.5 py-2 rounded-xl text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 transition-colors flex items-center gap-1.5">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span>Buka Kuesioner SUS</span>
                </button>
            </div>
        </div>
    </section>

    <!-- Usability HUD Component -->
    @include('cps.partials.usability-hud')

    <!-- Predefined Test Scenarios Cards Grid -->
    <section class="space-y-3">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 font-mono">DAFTAR SKENARIO TUGAS UJI (TASK SCENARIOS)</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            <!-- Task 1 Card -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs hover:border-indigo-300 transition-all space-y-2">
                <div class="flex items-center justify-between">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-indigo-100 text-indigo-800">TUGAS T1</span>
                    <span class="text-[10px] text-slate-400 font-mono">Target: &lt; 5 Detik</span>
                </div>
                <h4 class="font-bold text-xs text-slate-900">Identifikasi Log Verifikasi Gagal Terkini</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Operator diminta melihat kartu focal live, memeriksa nama dan alasan kegagalan wajah yang tidak terdaftar.
                </p>
                <div class="pt-2">
                    <button onclick="selectAndStartTask('T1|Identifikasi Log Verifikasi Gagal Terkini')" class="w-full py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-play text-[10px] text-indigo-600"></i>
                        <span>Pilih &amp; Mulai Tugas T1</span>
                    </button>
                </div>
            </div>

            <!-- Task 2 Card -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs hover:border-indigo-300 transition-all space-y-2">
                <div class="flex items-center justify-between">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-indigo-100 text-indigo-800">TUGAS T2</span>
                    <span class="text-[10px] text-slate-400 font-mono">Target: &lt; 6 Detik</span>
                </div>
                <h4 class="font-bold text-xs text-slate-900">Filter Riwayat Berdasarkan Status Verified</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Operator beralih ke tab riwayat data, mengaktifkan filter status 'Verified', dan memeriksa personel yang lolos.
                </p>
                <div class="pt-2">
                    <button onclick="selectAndStartTask('T2|Filter Riwayat Berdasarkan Status Verified')" class="w-full py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-play text-[10px] text-indigo-600"></i>
                        <span>Pilih &amp; Mulai Tugas T2</span>
                    </button>
                </div>
            </div>

            <!-- Task 3 Card -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs hover:border-indigo-300 transition-all space-y-2">
                <div class="flex items-center justify-between">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-indigo-100 text-indigo-800">TUGAS T3</span>
                    <span class="text-[10px] text-slate-400 font-mono">Target: &lt; 5 Detik</span>
                </div>
                <h4 class="font-bold text-xs text-slate-900">Lakukan Tindakan Otorisasi / Manual Override</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Operator menekan tombol 'Otorisasi Akses (Approve)' atau 'Tolak Akses' pada kartu hero atau tombol thumb bar.
                </p>
                <div class="pt-2">
                    <button onclick="selectAndStartTask('T3|Lakukan Tindakan Otorisasi / Manual Override')" class="w-full py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-play text-[10px] text-indigo-600"></i>
                        <span>Pilih &amp; Mulai Tugas T3</span>
                    </button>
                </div>
            </div>

            <!-- Task 4 Card -->
            <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-2xs hover:border-indigo-300 transition-all space-y-2">
                <div class="flex items-center justify-between">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-mono font-bold bg-indigo-100 text-indigo-800">TUGAS T4</span>
                    <span class="text-[10px] text-slate-400 font-mono">Target: &lt; 4 Detik</span>
                </div>
                <h4 class="font-bold text-xs text-slate-900">Simulasi Pemindaian Biometrik Baru</h4>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Operator menekan tombol simulasi pemindaian kamera gerbang dan memverifikasi data baru yang masuk secara live.
                </p>
                <div class="pt-2">
                    <button onclick="selectAndStartTask('T4|Simulasi Pemindaian Biometrik Baru')" class="w-full py-1.5 rounded-lg text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-play text-[10px] text-indigo-600"></i>
                        <span>Pilih &amp; Mulai Tugas T4</span>
                    </button>
                </div>
            </div>

        </div>
    </section>

</div>
