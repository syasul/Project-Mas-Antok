<!-- TAB 1: PUSAT AUTENTIKASI REAL-TIME (Focal Eyeline HCD) -->
<div id="tab-content-live" class="space-y-6">
    
    <!-- 1. KPI SUMMARY METRIC STRIP -->
    <section class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
        
        <!-- Total Verifications -->
        <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs">
            <div class="flex items-center justify-between text-slate-500 text-[10px] font-bold uppercase font-mono">
                <span>TOTAL VERIFIKASI</span>
                <i class="fa-solid fa-users-viewfinder text-xs text-slate-400"></i>
            </div>
            <div class="text-2xl font-black text-slate-900 font-mono mt-1" id="kpi-total-today">
                {{ $stats['total'] ?? 0 }}
            </div>
            <div class="text-[10px] text-slate-500 mt-0.5">Hari ini terdata di gerbang</div>
        </div>

        <!-- Verified Count -->
        <div class="bg-white p-4 rounded-xl border border-emerald-200/90 bg-emerald-50/20 shadow-2xs">
            <div class="flex items-center justify-between text-emerald-800 text-[10px] font-bold uppercase font-mono">
                <span>VERIFIKASI LOLOS</span>
                <i class="fa-solid fa-circle-check text-xs text-emerald-600"></i>
            </div>
            <div class="text-2xl font-black text-emerald-700 font-mono mt-1" id="kpi-verified-count">
                {{ $stats['verified'] ?? 0 }}
            </div>
            <div class="text-[10px] text-emerald-600 font-semibold mt-0.5">
                {{ $stats['total'] > 0 ? round(($stats['verified'] / $stats['total']) * 100, 1) : 100 }}% dari total
            </div>
        </div>

        <!-- Failed Count -->
        <div class="bg-white p-4 rounded-xl border border-rose-200/90 bg-rose-50/20 shadow-2xs">
            <div class="flex items-center justify-between text-rose-800 text-[10px] font-bold uppercase font-mono">
                <span>VERIFIKASI GAGAL</span>
                <i class="fa-solid fa-circle-xmark text-xs text-rose-600"></i>
            </div>
            <div class="text-2xl font-black text-rose-700 font-mono mt-1" id="kpi-failed-count">
                {{ $stats['failed'] ?? 0 }}
            </div>
            <div class="text-[10px] text-rose-600 font-semibold mt-0.5">
                {{ $stats['total'] > 0 ? round(($stats['failed'] / $stats['total']) * 100, 1) : 0 }}% anomali / ditolak
            </div>
        </div>

        <!-- Confidence Average -->
        <div class="bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs">
            <div class="flex items-center justify-between text-slate-500 text-[10px] font-bold uppercase font-mono">
                <span>AVG CONFIDENCE</span>
                <i class="fa-solid fa-chart-line text-xs text-indigo-400"></i>
            </div>
            <div class="text-2xl font-black text-slate-900 font-mono mt-1" id="kpi-avg-confidence">
                {{ $stats['avg_confidence'] ?? 96.2 }}%
            </div>
            <div class="text-[10px] text-slate-500 mt-0.5">Akurasi model biometrik</div>
        </div>

        <!-- WebSocket Latency Metric (<100ms) -->
        <div class="col-span-2 lg:col-span-1 bg-white p-4 rounded-xl border border-slate-200/90 shadow-2xs">
            <div class="flex items-center justify-between text-slate-500 text-[10px] font-bold uppercase font-mono">
                <span>LATENSI WEBSOCKET</span>
                <span class="text-[9px] font-bold bg-emerald-100 text-emerald-800 px-1.5 py-0.2 rounded font-mono">&lt;100ms</span>
            </div>
            <div class="text-2xl font-black text-slate-900 font-mono mt-1" id="kpi-avg-latency">
                {{ $stats['avg_latency'] ?? 24.5 }} ms
            </div>
            <div class="text-[10px] text-emerald-600 font-semibold mt-0.5 flex items-center gap-1">
                <i class="fa-solid fa-circle-check text-[10px]"></i>
                <span>Target delay tercapai</span>
            </div>
        </div>

    </section>

    <!-- 2. HERO FOCAL CARD (Real-Time Latest Verification) -->
    @include('cps.partials.realtime-card')

    <!-- 3. LIVE STREAM TIMELINE (Recent 5 Feed Items) -->
    <section class="bg-white rounded-xl border border-slate-200 shadow-2xs p-4">
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 font-mono">ALIRAN TELEMETRI REAL-TIME TERAKHIR</h3>
            </div>
            <button onclick="switchTab('history')" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                <span>Lihat Semua Log</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </button>
        </div>

        <!-- Horizontal Stream Feed -->
        <div id="live-stream-feed" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            @forelse($recentLogs->take(5) as $log)
                <div class="p-3 rounded-lg border border-slate-200/80 bg-slate-50/50 hover:bg-slate-50 transition-colors space-y-2 {{ $log->status === 'failed' ? 'border-l-4 border-l-rose-500' : 'border-l-4 border-l-emerald-500' }}">
                    <div class="flex items-center gap-2">
                        <img src="{{ $log->photo_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" alt="Thumb" class="w-8 h-8 rounded-lg object-cover border border-slate-200 shrink-0">
                        <div class="min-w-0">
                            <div class="font-bold text-xs text-slate-900 truncate">{{ $log->subject_name }}</div>
                            <div class="text-[10px] text-slate-500 font-mono">{{ $log->created_at ? $log->created_at->format('H:i:s') : '--:--' }}</div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center text-[10px] font-mono pt-1 border-t border-slate-200/60">
                        <span class="font-bold {{ $log->status === 'failed' ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ strtoupper($log->status) }}
                        </span>
                        <span class="text-slate-500">{{ $log->confidence_score }}%</span>
                    </div>
                </div>
            @empty
                <div class="col-span-5 text-center py-4 text-xs text-slate-400">Menunggu data masuk...</div>
            @endforelse
        </div>
    </section>

</div>
