<!-- USABILITY TESTING HUD & TASK COMPLETION TIME (TCT) ASSISTANT (HCD Evaluation Tool) -->
<section class="bg-white rounded-xl border border-slate-200 shadow-2xs p-4">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        
        <!-- HUD Info & Header -->
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                <i class="fa-solid fa-stopwatch text-base"></i>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-indigo-900 font-mono">USABILITY TESTING ASSISTANT</h3>
                    <span class="px-2 py-0.2 rounded-full text-[9px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200 font-mono">HCD EVALUATOR</span>
                </div>
                <p class="text-[11px] text-slate-500 mt-0.5">Pengukur otomatis <em>Task Completion Time (TCT)</em> &amp; <em>Error Rate</em> untuk pengujian operator Poltekad</p>
            </div>
        </div>

        <!-- Task Selector & Live Stopwatch -->
        <div class="flex flex-wrap items-center gap-3">
            
            <!-- Task Dropdown -->
            <div class="min-w-[240px]">
                <label for="usability-task-select" class="block text-[10px] font-bold text-slate-500 uppercase font-mono mb-1">PILIH SKENARIO TUGAS UJI:</label>
                <select id="usability-task-select" class="w-full text-xs font-semibold text-slate-800 bg-white border border-slate-300 rounded-xl px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="T1|Identifikasi Log Verifikasi Gagal Terkini">T1: Identifikasi Log Verifikasi Gagal Terkini</option>
                    <option value="T2|Filter Riwayat Berdasarkan Status Verified">T2: Filter Riwayat Berdasarkan Status Verified</option>
                    <option value="T3|Lakukan Tindakan Otorisasi / Manual Override">T3: Lakukan Tindakan Otorisasi / Manual Override</option>
                    <option value="T4|Simulasi Pemindaian Biometrik Baru">T4: Simulasi Pemindaian Biometrik Baru</option>
                </select>
            </div>

            <!-- Live Timer Counter Box -->
            <div class="bg-slate-900 text-white px-3.5 py-1.5 rounded-xl border border-slate-800 flex items-center gap-3 font-mono shadow-inner">
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">TIMER TCT:</span>
                    <span id="usability-timer-display" class="text-sm font-extrabold text-emerald-400">00:00.0</span>
                </div>
                <div class="h-6 w-px bg-slate-700"></div>
                <div>
                    <span class="text-[9px] text-slate-400 block font-sans">MISCLICKS:</span>
                    <span id="usability-misclick-display" class="text-sm font-extrabold text-amber-400">0</span>
                </div>
            </div>

            <!-- Control Buttons -->
            <div class="flex items-center gap-2">
                <button id="btn-start-usability-task" onclick="toggleUsabilityTask()" class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all flex items-center gap-1.5 shadow-xs">
                    <i class="fa-solid fa-play text-[11px]" id="icon-task-btn"></i>
                    <span id="text-task-btn">Mulai Uji Tugas</span>
                </button>
                <a href="{{ route('usability.sus') }}" class="px-3.5 py-2 rounded-xl text-xs font-bold text-indigo-700 bg-white border border-indigo-300 hover:bg-indigo-50 active:scale-95 transition-all flex items-center gap-1.5 shadow-2xs">
                    <i class="fa-solid fa-file-pen text-[11px]"></i>
                    <span>Isi SUS Form</span>
                </a>
            </div>

        </div>

    </div>
</section>
