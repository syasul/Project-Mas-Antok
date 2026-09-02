<!-- FOCAL EYELINE: HERO REAL-TIME VERIFICATION CARD (HCD Primary Focus) -->
<section id="hero-verification-container" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 transition-all duration-300">
    
    <!-- Card Header with Section Title & Live Badge -->
    <div class="flex items-center justify-between gap-2 mb-4 pb-3 border-b border-slate-100">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                <i class="fa-solid fa-id-badge text-sm"></i>
            </div>
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-slate-500 font-mono">AUTENTIKASI WAJAH TERKINI</h2>
                <p class="text-[11px] text-slate-400">Pembaruan otomatis via WebSocket sub-100ms</p>
            </div>
        </div>

        <!-- Real-time Update Pulse Pill -->
        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-[10px] font-bold text-slate-700 font-mono">
            <i class="fa-solid fa-satellite-dish text-emerald-500 animate-pulse text-[10px]"></i>
            <span id="hero-time-ago">Baru Saja</span>
        </div>
    </div>

    <!-- Main Card Body: 2 Columns (Left: Photo + Bounding Box, Right: Details + Confidence) -->
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-5 items-center">
        
        <!-- Left: Face Photo Preview with Liveness HUD -->
        <div class="sm:col-span-4 lg:col-span-3 flex flex-col items-center">
            <div class="relative w-36 h-36 sm:w-full sm:h-44 max-w-[180px] rounded-2xl overflow-hidden border-2 border-slate-200 bg-slate-100 shadow-xs group">
                <img id="hero-photo" src="{{ $latestLog->photo_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&auto=format&fit=crop&q=80' }}" alt="Foto Verifikasi" class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105">
                
                <!-- Bounding Box HUD Overlay -->
                <div class="absolute inset-2 border-2 border-dashed border-emerald-400/80 rounded-xl pointer-events-none flex flex-col justify-between p-1.5">
                    <div class="flex justify-between items-start">
                        <span class="text-[8px] bg-slate-900/80 text-emerald-400 px-1 py-0.2 rounded font-mono font-bold">CPS-AI</span>
                        <i class="fa-solid fa-expand text-[10px] text-emerald-400"></i>
                    </div>
                    <div class="flex justify-between items-end">
                        <span id="hero-liveness-badge" class="text-[8px] bg-emerald-600 text-white px-1.5 py-0.2 rounded font-mono font-bold uppercase">LIVE: 30 FPS</span>
                    </div>
                </div>

                <!-- Status Overlay Ribbon if Failed -->
                <div id="hero-failed-ribbon" class="{{ ($latestLog->status ?? 'verified') === 'failed' ? '' : 'hidden' }} absolute inset-0 bg-rose-900/40 backdrop-blur-[1px] flex items-center justify-center">
                    <span class="bg-rose-600 text-white font-extrabold text-[10px] uppercase px-2 py-1 rounded-md shadow-md border border-white/30 tracking-wider">TIDAK DIKENAL</span>
                </div>
            </div>
        </div>

        <!-- Right: Subject Info, Confidence Meter & Semantic Status -->
        <div class="sm:col-span-8 lg:col-span-9 space-y-3.5">
            
            <!-- Status Badge & Category -->
            <div class="flex flex-wrap items-center gap-2">
                
                <!-- Semantic Status Badge -->
                <div id="hero-status-badge-container">
                    @php
                        $status = $latestLog->status ?? 'verified';
                    @endphp

                    @if($status === 'verified')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-300 shadow-2xs font-mono">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>TERVERIFIKASI (VERIFIED)</span>
                        </span>
                    @elseif($status === 'failed')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-300 shadow-2xs font-mono">
                            <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                            <span>VERIFIKASI GAGAL (FAILED)</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-300 shadow-2xs font-mono">
                            <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                            <span>MENUNGGU REVIEW (PENDING)</span>
                        </span>
                    @endif
                </div>

                <!-- Subject Category Pill -->
                <span id="hero-category-badge" class="px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200 uppercase font-mono">
                    {{ $latestLog->category ?? 'Taruna' }}
                </span>

                <!-- Device Latency Badge -->
                <span class="ml-auto inline-flex items-center gap-1 text-[11px] font-mono text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-200">
                    <i class="fa-solid fa-bolt text-amber-500 text-[10px]"></i>
                    <span id="hero-latency-text">{{ $latestLog->latency_ms ?? 22.4 }} ms</span>
                </span>
            </div>

            <!-- Subject Full Name & Identifier -->
            <div>
                <h3 id="hero-subject-name" class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight leading-snug">
                    {{ $latestLog->subject_name ?? 'Sersan Mayor Dua Taruna Arya Pratama' }}
                </h3>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1 text-xs text-slate-500">
                    <div class="flex items-center gap-1 font-mono">
                        <span class="text-slate-400">NIM/NRP:</span>
                        <span id="hero-subject-nim" class="font-bold text-slate-800">{{ $latestLog->nim ?? '2024.01.0042' }}</span>
                    </div>
                    <div class="flex items-center gap-1">
                        <i class="fa-solid fa-location-dot text-slate-400 text-[11px]"></i>
                        <span id="hero-location" class="font-medium text-slate-700">{{ $latestLog->location ?? 'Gate Utama (Pos 1 Poltekad)' }}</span>
                    </div>
                    <div class="flex items-center gap-1 font-mono text-[11px] text-slate-400">
                        <span id="hero-device-id">[{{ $latestLog->device_id ?? 'CAM_GATE_UTAMA_01' }}]</span>
                    </div>
                </div>
            </div>

            <!-- Confidence Score Progress Bar & Numerical Value -->
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
                <div class="flex justify-between items-center text-xs mb-1.5">
                    <span class="font-bold text-slate-700 uppercase tracking-wider text-[10px] font-mono">TINGKAT KECOCOKAN BIOMETRIK (CONFIDENCE SCORE)</span>
                    <span id="hero-confidence-val" class="font-extrabold text-sm font-mono text-slate-900">
                        {{ $latestLog->confidence_score ?? 98.4 }}%
                    </span>
                </div>
                <!-- Bar -->
                <div class="w-full bg-slate-200 rounded-full h-2.5 overflow-hidden">
                    <div id="hero-confidence-bar" class="h-full rounded-full transition-all duration-500 {{ ($latestLog->status ?? 'verified') === 'failed' ? 'bg-rose-500' : 'bg-emerald-500' }}" style="width: {{ min(100, $latestLog->confidence_score ?? 98.4) }}%"></div>
                </div>
                
                <!-- Failure Note if applicable -->
                <div id="hero-failure-note-container" class="{{ !empty($latestLog->failure_reason) ? '' : 'hidden' }} mt-2 text-[11px] text-rose-700 font-medium bg-rose-50 p-2 rounded-lg border border-rose-200 flex items-start gap-1.5">
                    <i class="fa-solid fa-triangle-exclamation text-rose-500 mt-0.5 shrink-0"></i>
                    <span id="hero-failure-note">{{ $latestLog->failure_reason ?? '' }}</span>
                </div>
            </div>

            <!-- Quick Action Buttons for Operator (Focal Area) -->
            <div class="flex flex-wrap items-center gap-2 pt-1">
                <button onclick="handleManualOverride('approve')" class="px-3.5 py-2 rounded-xl text-xs font-bold text-emerald-800 bg-emerald-50 border border-emerald-300 hover:bg-emerald-100 active:scale-95 transition-all flex items-center gap-1.5 shadow-2xs">
                    <i class="fa-solid fa-check"></i>
                    <span>Otorisasi Akses (Approve)</span>
                </button>
                <button onclick="handleManualOverride('reject')" class="px-3.5 py-2 rounded-xl text-xs font-bold text-rose-800 bg-rose-50 border border-rose-300 hover:bg-rose-100 active:scale-95 transition-all flex items-center gap-1.5 shadow-2xs">
                    <i class="fa-solid fa-ban"></i>
                    <span>Tolak / Tandai Anomali</span>
                </button>
                <button onclick="triggerSimulatedScan('random')" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 active:scale-95 transition-all flex items-center gap-1.5 ml-auto shadow-2xs">
                    <i class="fa-solid fa-rotate text-slate-500"></i>
                    <span>Simulasi Scan Baru</span>
                </button>
            </div>

        </div>

    </div>

</section>
