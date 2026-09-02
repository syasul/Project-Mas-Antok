<div id="tab-content-decision" class="tab-pane hidden space-y-6">
    <div class="grid grid-cols-1 gap-6">
        <section class="glass-card rounded-2xl p-5 border border-slate-200/80 bg-white/70 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4 border-b border-slate-100 pb-3">
                <div>
                    <h3 class="text-xs font-bold tracking-wider text-slate-800 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-brain text-indigo-500"></i>
                        Log Keputusan & Audit Tindakan Pertahanan
                    </h3>
                    <p class="text-[10px] text-slate-400 font-mono">Evaluasi IF-THEN Rules Expert System & Rekapitulasi Ancaman</p>
                </div>

                <!-- Export & Action Toolbar -->
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[9px] bg-emerald-100 text-emerald-700 font-bold px-2.5 py-1 rounded-full font-mono border border-emerald-200">
                        AKURASI ENGINE: 100%
                    </span>
                    
                    <button onclick="dispatchTelegramAlert()" class="px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-700 border border-sky-200 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5" title="Kirim Notifikasi Darurat ke Telegram Komando">
                        <i class="fa-brands fa-telegram text-sky-500"></i>
                        <span>Kirim Telegram</span>
                    </button>

                    <a href="{{ route('reports.decisions-csv') }}" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5" title="Unduh Log Keputusan format CSV">
                        <i class="fa-solid fa-file-csv text-emerald-600"></i>
                        <span>CSV Keputusan</span>
                    </a>

                    <a href="{{ route('reports.sensors-csv') }}" class="px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5" title="Unduh Telemetri Sensor format CSV">
                        <i class="fa-solid fa-table text-indigo-600"></i>
                        <span>CSV Telemetri</span>
                    </a>

                    <a href="{{ route('reports.incident-pdf') }}" target="_blank" class="px-3.5 py-1.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white rounded-xl text-xs font-bold transition-all shadow-md shadow-emerald-700/20 flex items-center gap-1.5" title="Buka Cetak Laporan PDF Resmi Militer">
                        <i class="fa-solid fa-file-pdf"></i>
                        <span>Cetak Laporan PDF</span>
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto w-full">
                <table class="w-full text-xs font-mono text-left">
                    <thead>
                        <tr class="border-b border-slate-100 text-slate-400 font-bold">
                            <th class="py-2.5 px-2">WAKTU</th>
                            <th class="py-2.5 px-2">KATEGORI EVENT</th>
                            <th class="py-2.5 px-2">LOGIKA ATURAN</th>
                            <th class="py-2.5 px-2">RESPONS OTOMATIS</th>
                        </tr>
                    </thead>
                    <tbody id="tab-decision-log-tbody" class="divide-y divide-slate-50">
                        <tr>
                            <td colspan="4" class="py-4 text-center text-slate-400">Sedang memuat data dari basis data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
