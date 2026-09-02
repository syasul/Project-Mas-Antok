<!-- TAB 5: ANALISIS HASIL RISET & STATISTIK KUANTITATIF -->
<div id="tab-content-analytics" class="hidden space-y-6">
    
    <!-- Header Banner -->
    <section class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-teal-500/10 border border-teal-500/20 text-teal-700 flex items-center justify-center font-bold text-lg shrink-0">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <h2 class="text-sm font-extrabold text-slate-900 uppercase font-mono tracking-tight">ANALISIS KUANTITATIF DESKRIPTIF PENELITIAN</h2>
                    <p class="text-xs text-slate-500">Evaluasi Metrik Usability (SUS, TCT, Error Rate) &amp; Kinerja WebSocket Latensi</p>
                </div>
            </div>

            <button onclick="window.print()" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors flex items-center gap-1.5 self-start sm:self-auto">
                <i class="fa-solid fa-print text-slate-500"></i>
                <span>Cetak Laporan Riset</span>
            </button>
        </div>
    </section>

    <!-- KPI Analytics Cards -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" id="analytics-kpi-container">
        
        <div class="bg-white p-4 rounded-xl border border-emerald-200/90 bg-emerald-50/20 shadow-2xs">
            <div class="flex justify-between items-start text-emerald-800 text-[10px] font-bold uppercase font-mono">
                <span>RATA-RATA SKOR SUS</span>
                <span class="px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800 font-mono text-[9px]">&gt;75 TARGET</span>
            </div>
            <div class="text-2xl font-black text-emerald-700 font-mono mt-1" id="stat-avg-sus">
                83.5
            </div>
            <div class="text-[11px] text-emerald-700 font-semibold mt-0.5">
                Grade A (Excellent / Acceptable)
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs">
            <div class="flex justify-between items-start text-slate-500 text-[10px] font-bold uppercase font-mono">
                <span>TOTAL RESPONDEN SUS</span>
                <i class="fa-solid fa-users text-xs text-slate-400"></i>
            </div>
            <div class="text-2xl font-black text-slate-900 font-mono mt-1" id="stat-total-respondents">
                5
            </div>
            <div class="text-[11px] text-slate-500 mt-0.5">
                Operator Poltekad terdaftar
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs">
            <div class="flex justify-between items-start text-slate-500 text-[10px] font-bold uppercase font-mono">
                <span>RATA-RATA TCT (DURASI TUGAS)</span>
                <i class="fa-solid fa-stopwatch text-xs text-amber-500"></i>
            </div>
            <div class="text-2xl font-black text-slate-900 font-mono mt-1" id="stat-avg-tct">
                4.26 dtk
            </div>
            <div class="text-[11px] text-indigo-700 font-medium mt-0.5">
                Efisiensi navigasi tinggi
            </div>
        </div>

        <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs">
            <div class="flex justify-between items-start text-slate-500 text-[10px] font-bold uppercase font-mono">
                <span>LATENSI WEBSOCKET AVG</span>
                <span class="px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800 font-mono text-[9px]">&lt;100ms OK</span>
            </div>
            <div class="text-2xl font-black text-slate-900 font-mono mt-1" id="stat-avg-ws-lat">
                30.6 ms
            </div>
            <div class="text-[11px] text-emerald-700 font-semibold mt-0.5">
                100% data sub-100ms
            </div>
        </div>

    </section>

    <!-- Table of SUS Survey Responses -->
    <section class="bg-white rounded-xl border border-slate-200 shadow-2xs p-5 space-y-3">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-900 font-mono">TABEL JAWABAN KUESIONER SYSTEM USABILITY SCALE (SUS)</h3>
            <button onclick="fetchUsabilityStats()" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">
                <i class="fa-solid fa-arrows-rotate text-[10px]"></i> Refresh Data
            </button>
        </div>

        <div class="overflow-x-auto rounded-lg border border-slate-100">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 uppercase font-mono text-[10px] border-b border-slate-200">
                        <th class="py-2.5 px-3">Responden</th>
                        <th class="py-2.5 px-1.5 text-center">Q1</th>
                        <th class="py-2.5 px-1.5 text-center">Q2</th>
                        <th class="py-2.5 px-1.5 text-center">Q3</th>
                        <th class="py-2.5 px-1.5 text-center">Q4</th>
                        <th class="py-2.5 px-1.5 text-center">Q5</th>
                        <th class="py-2.5 px-1.5 text-center">Q6</th>
                        <th class="py-2.5 px-1.5 text-center">Q7</th>
                        <th class="py-2.5 px-1.5 text-center">Q8</th>
                        <th class="py-2.5 px-1.5 text-center">Q9</th>
                        <th class="py-2.5 px-1.5 text-center">Q10</th>
                        <th class="py-2.5 px-3 text-right font-bold">Skor SUS</th>
                        <th class="py-2.5 px-3">Grade</th>
                    </tr>
                </thead>
                <tbody id="table-sus-responses-body" class="divide-y divide-slate-100 font-mono text-slate-700">
                    <!-- Populated dynamically via JS / API -->
                    <tr>
                        <td colspan="13" class="text-center py-4 text-slate-400 font-sans">Memuat data respon SUS...</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

</div>
