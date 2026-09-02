<!-- THUMB ZONE BOTTOM ACTION BAR (Khusus Mobile & Tablet Operator Lapangan) -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-2.5 shadow-lg">
    <div class="max-w-lg mx-auto grid grid-cols-4 gap-2">
        
        <!-- Action 1: Approve Button (Thumb Left) -->
        <button onclick="handleManualOverride('approve')" class="flex flex-col items-center justify-center py-2 px-1 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-300 active:bg-emerald-100 min-h-[48px] shadow-2xs transition-transform active:scale-95">
            <i class="fa-solid fa-circle-check text-base text-emerald-600"></i>
            <span class="text-[10px] font-bold mt-0.5 tracking-tight font-mono">Approve</span>
        </button>

        <!-- Action 2: Reject / Flag (Thumb Center Left) -->
        <button onclick="handleManualOverride('reject')" class="flex flex-col items-center justify-center py-2 px-1 rounded-xl bg-rose-50 text-rose-700 border border-rose-300 active:bg-rose-100 min-h-[48px] shadow-2xs transition-transform active:scale-95">
            <i class="fa-solid fa-circle-xmark text-base text-rose-600"></i>
            <span class="text-[10px] font-bold mt-0.5 tracking-tight font-mono">Tolak</span>
        </button>

        <!-- Action 3: Trigger Live Scan (Thumb Center Right) -->
        <button onclick="triggerSimulatedScan('random')" class="flex flex-col items-center justify-center py-2 px-1 rounded-xl bg-indigo-600 text-white active:bg-indigo-700 min-h-[48px] shadow-xs transition-transform active:scale-95">
            <i class="fa-solid fa-camera-viewfinder text-base"></i>
            <span class="text-[10px] font-bold mt-0.5 tracking-tight font-mono">Scan Baru</span>
        </button>

        <!-- Action 4: SUS Evaluation / Usability HUD (Thumb Right) -->
        <a href="{{ route('usability.sus') }}" class="flex flex-col items-center justify-center py-2 px-1 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 active:bg-slate-200 min-h-[48px] shadow-2xs transition-transform active:scale-95">
            <i class="fa-solid fa-clipboard-check text-base text-indigo-600"></i>
            <span class="text-[10px] font-bold mt-0.5 tracking-tight font-mono">Isi SUS</span>
        </a>

    </div>
</nav>
