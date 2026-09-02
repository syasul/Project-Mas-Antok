<!-- RIWAYAT LOG VERIFIKASI AUTENTIKASI WAJAH (Responsive Table on Desktop / Card-List on Mobile) -->
<section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
    
    <!-- Section Header & Filter Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-3 border-b border-slate-100">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-700">
                <i class="fa-solid fa-list-check text-sm"></i>
            </div>
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700 font-mono">RIWAYAT LOG AUTENTIKASI LENGKAP</h2>
                <p class="text-[11px] text-slate-400">Penyaringan data verifikasi biometrik realtime</p>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="flex flex-wrap items-center gap-2">
            
            <!-- Status Filter Tabs -->
            <div class="inline-flex bg-slate-100 p-0.5 rounded-xl border border-slate-200 text-xs font-semibold font-mono">
                <button onclick="filterByStatus('all')" id="tab-filter-all" class="px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-2xs transition-all">SEMUA</button>
                <button onclick="filterByStatus('verified')" id="tab-filter-verified" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-emerald-700 transition-all flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>VERIFIED</span>
                </button>
                <button onclick="filterByStatus('failed')" id="tab-filter-failed" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-rose-700 transition-all flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    <span>FAILED</span>
                </button>
                <button onclick="filterByStatus('pending')" id="tab-filter-pending" class="px-3 py-1.5 rounded-lg text-slate-600 hover:text-amber-700 transition-all flex items-center gap-1">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span>PENDING</span>
                </button>
            </div>

            <!-- Search Input -->
            <div class="relative min-w-[200px] flex-1 sm:flex-initial">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                <input type="text" id="log-search-input" onkeyup="handleSearchLogs(this.value)" placeholder="Cari nama, NIM, pos..." class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
            </div>

        </div>
    </div>

    <!-- 1. DESKTOP VIEW: Data Grid Table (Hidden on Mobile) -->
    <div class="hidden md:block overflow-x-auto rounded-xl border border-slate-100">
        <table class="w-full text-left border-collapse text-xs">
            <thead>
                <tr class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-mono text-[10px] border-b border-slate-200">
                    <th class="py-3 px-3.5">Waktu</th>
                    <th class="py-3 px-3.5">Foto</th>
                    <th class="py-3 px-3.5">Nama Personel & NIM</th>
                    <th class="py-3 px-3.5">Pos / Gerbang</th>
                    <th class="py-3 px-3.5">Confidence</th>
                    <th class="py-3 px-3.5">Status</th>
                    <th class="py-3 px-3.5 text-right">Latensi</th>
                    <th class="py-3 px-3.5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="logs-table-body" class="divide-y divide-slate-100 font-medium text-slate-700">
                @forelse($recentLogs as $log)
                    <tr class="hover:bg-slate-50/60 transition-colors {{ $log->status === 'failed' ? 'bg-rose-50/20' : '' }}" id="row-log-{{ $log->id }}">
                        
                        <!-- Timestamp -->
                        <td class="py-3 px-3.5 font-mono text-[11px] text-slate-500 whitespace-nowrap">
                            {{ $log->created_at ? $log->created_at->format('H:i:s') : '--:--:--' }}
                            <div class="text-[9px] text-slate-400">{{ $log->created_at ? $log->created_at->format('d/m/Y') : '' }}</div>
                        </td>

                        <!-- Photo Thumbnail -->
                        <td class="py-3 px-3.5 whitespace-nowrap">
                            <img src="{{ $log->photo_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" alt="Thumb" class="w-9 h-9 rounded-lg object-cover border border-slate-200 shadow-2xs">
                        </td>

                        <!-- Subject Name & NIM -->
                        <td class="py-3 px-3.5">
                            <div class="font-bold text-slate-900">{{ $log->subject_name }}</div>
                            <div class="flex items-center gap-1.5 text-[11px] text-slate-500 font-mono">
                                <span>{{ $log->nim ?? 'N/A' }}</span>
                                <span class="text-slate-300">•</span>
                                <span class="text-slate-600 font-semibold">{{ $log->category }}</span>
                            </div>
                        </td>

                        <!-- Location & Device ID -->
                        <td class="py-3 px-3.5">
                            <div class="text-slate-800">{{ $log->location }}</div>
                            <div class="text-[10px] font-mono text-slate-400">{{ $log->device_id }}</div>
                        </td>

                        <!-- Confidence Score Bar -->
                        <td class="py-3 px-3.5 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="font-bold font-mono text-xs text-slate-800">{{ $log->confidence_score }}%</span>
                                <div class="w-12 bg-slate-200 rounded-full h-1.5">
                                    <div class="h-full rounded-full {{ $log->status === 'failed' ? 'bg-rose-500' : 'bg-emerald-500' }}" style="width: {{ min(100, $log->confidence_score) }}%"></div>
                                </div>
                            </div>
                        </td>

                        <!-- Status Badge -->
                        <td class="py-3 px-3.5 whitespace-nowrap">
                            @if($log->status === 'verified')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono">
                                    <i class="fa-solid fa-check text-[10px]"></i>
                                    <span>VERIFIED</span>
                                </span>
                            @elseif($log->status === 'failed')
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 font-mono" title="{{ $log->failure_reason }}">
                                    <i class="fa-solid fa-xmark text-[10px]"></i>
                                    <span>FAILED</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 font-mono">
                                    <i class="fa-solid fa-clock text-[10px]"></i>
                                    <span>PENDING</span>
                                </span>
                            @endif
                        </td>

                        <!-- Latency ms -->
                        <td class="py-3 px-3.5 text-right font-mono text-[11px] text-slate-600 whitespace-nowrap">
                            {{ $log->latency_ms }} ms
                        </td>

                        <!-- Action buttons -->
                        <td class="py-3 px-3.5 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1">
                                <button onclick="quickOverride({{ $log->id }}, 'approve')" title="Setujui Akses" class="w-7 h-7 rounded-lg flex items-center justify-center bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 transition-colors">
                                    <i class="fa-solid fa-check text-[11px]"></i>
                                </button>
                                <button onclick="quickOverride({{ $log->id }}, 'reject')" title="Tolak / Flag" class="w-7 h-7 rounded-lg flex items-center justify-center bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 transition-colors">
                                    <i class="fa-solid fa-ban text-[11px]"></i>
                                </button>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-slate-400 font-medium">Belum ada data log verifikasi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- 2. MOBILE VIEW: Card-List (No Horizontal Scrolling, Ergonomic Thumb Browsing) -->
    <div id="logs-mobile-container" class="md:hidden space-y-3">
        @forelse($recentLogs as $log)
            <div class="p-3.5 rounded-xl border border-slate-200 bg-white shadow-2xs space-y-2 {{ $log->status === 'failed' ? 'border-l-4 border-l-rose-500 bg-rose-50/20' : 'border-l-4 border-l-emerald-500' }}" id="mob-log-{{ $log->id }}">
                
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ $log->photo_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" alt="Thumb" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shrink-0">
                        <div>
                            <div class="font-bold text-xs text-slate-900 line-clamp-1">{{ $log->subject_name }}</div>
                            <div class="text-[10px] text-slate-500 font-mono">{{ $log->nim ?? 'N/A' }} • {{ $log->category }}</div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        @if($log->status === 'verified')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 font-mono">VERIFIED</span>
                        @elseif($log->status === 'failed')
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 font-mono">FAILED</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 font-mono">PENDING</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-between text-[10px] text-slate-500 pt-1 border-t border-slate-100 font-mono">
                    <span>{{ $log->location }}</span>
                    <span>Confidence: <strong class="text-slate-800">{{ $log->confidence_score }}%</strong></span>
                    <span>Latensi: <strong class="text-slate-800">{{ $log->latency_ms }} ms</strong></span>
                </div>

                @if(!empty($log->failure_reason))
                    <div class="text-[10px] text-rose-700 bg-rose-50 p-1.5 rounded border border-rose-200">
                        {{ $log->failure_reason }}
                    </div>
                @endif

                <!-- Mobile Action Row -->
                <div class="flex items-center gap-2 pt-1">
                    <button onclick="quickOverride({{ $log->id }}, 'approve')" class="flex-1 py-1.5 text-center rounded-lg text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 active:bg-emerald-100">
                        Approve
                    </button>
                    <button onclick="quickOverride({{ $log->id }}, 'reject')" class="flex-1 py-1.5 text-center rounded-lg text-[11px] font-bold text-rose-700 bg-rose-50 border border-rose-200 active:bg-rose-100">
                        Tolak
                    </button>
                </div>

            </div>
        @empty
            <div class="text-center py-6 text-slate-400 text-xs font-medium">Belum ada riwayat verifikasi.</div>
        @endforelse
    </div>

</section>
