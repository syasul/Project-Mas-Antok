<!-- TAB 4: KUESIONER SYSTEM USABILITY SCALE (SUS) -->
<div id="tab-content-sus" class="hidden space-y-6">
    
    <!-- Header Banner -->
    <section class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold text-lg shrink-0 shadow-xs">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-extrabold text-slate-900 uppercase font-mono tracking-tight">EVALUASI SYSTEM USABILITY SCALE (SUS)</h2>
                        <span class="px-2 py-0.2 rounded-full text-[9px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200 font-mono">STANDAR JOHN BROOKE (1986)</span>
                    </div>
                    <p class="text-xs text-slate-500">Kuesioner 10 butir pertanyaan skala Likert 1–5 untuk validasi target skor SUS &gt; 75</p>
                </div>
            </div>

            <button onclick="switchTab('analytics')" class="px-3.5 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors flex items-center gap-1.5 self-start sm:self-auto">
                <i class="fa-solid fa-chart-simple text-slate-500"></i>
                <span>Lihat Rekapitulasi Skor</span>
            </button>
        </div>
    </section>

    <!-- Live Preview Sticky Card -->
    <section class="bg-slate-900 text-white rounded-xl p-4 shadow-sm border border-slate-800 flex flex-wrap items-center justify-between gap-4">
        <div>
            <span class="text-[10px] font-bold text-slate-400 uppercase font-mono block">ESTIMASI SKOR SUS ANDA:</span>
            <div class="flex items-baseline gap-2 mt-0.5">
                <span id="tab-live-sus-score" class="text-2xl font-black font-mono text-emerald-400">82.5</span>
                <span class="text-xs text-slate-400 font-mono">/ 100</span>
                <span id="tab-live-sus-grade" class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-900/80 text-emerald-300 border border-emerald-600 font-mono">GRADE A (EXCELLENT)</span>
            </div>
        </div>
        
        <div class="flex items-center gap-2 text-xs font-mono">
            <span class="text-slate-400">Indikator Penelitian:</span>
            <span id="tab-live-sus-target" class="px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/30">
                <i class="fa-solid fa-circle-check"></i> TARGET &gt;75 TERPENUHI
            </span>
        </div>
    </section>

    <!-- 10 Questions Form -->
    <form id="tab-sus-form" onsubmit="handleTabSusSubmit(event)" class="bg-white rounded-xl border border-slate-200 shadow-2xs p-5 sm:p-6 space-y-6">
        @csrf

        <!-- Respondent Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-slate-100">
            <div>
                <label for="tab_respondent_name" class="block text-xs font-bold text-slate-700 uppercase font-mono mb-1">NAMA OPERATOR <span class="text-rose-500">*</span></label>
                <input type="text" id="tab_respondent_name" name="respondent_name" value="{{ auth()->user()->name ?? 'Letnan Dua Antok' }}" required class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
            </div>
            <div>
                <label for="tab_respondent_role" class="block text-xs font-bold text-slate-700 uppercase font-mono mb-1">JABATAN OPERASIONAL</label>
                <select id="tab_respondent_role" name="respondent_role" class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium">
                    <option value="Perwira Jaga Komando">Perwira Jaga Komando</option>
                    <option value="Operator Pos Gerbang Utama">Operator Pos Gerbang Utama</option>
                    <option value="Operator Patroli Lapangan">Operator Patroli Lapangan</option>
                    <option value="Perwira Siber & Komunikasi">Perwira Siber & Komunikasi</option>
                    <option value="Operator Barak Taruna">Operator Barak Taruna</option>
                </select>
            </div>
        </div>

        <!-- 10 Questions List -->
        <div class="space-y-4">
            @php
                $questions = [
                    1 => "Saya berpikir bahwa saya akan sering menggunakan sistem dashboard autentikasi ini.",
                    2 => "Saya merasa sistem dashboard ini terlalu rumit padahal tidak perlu.",
                    3 => "Saya merasa sistem dashboard ini mudah untuk digunakan.",
                    4 => "Saya membutuhkan bantuan orang teknis atau manual khusus untuk dapat menggunakan sistem ini.",
                    5 => "Saya menemukan berbagai fungsi dan fitur dalam sistem ini terintegrasi dengan sangat baik.",
                    6 => "Saya merasa ada banyak inkonsistensi atau hal yang tidak serasi dalam sistem ini.",
                    7 => "Saya merasa sebagian besar operator akan cepat belajar menggunakan sistem ini dalam waktu singkat.",
                    8 => "Saya merasa sistem dashboard ini sangat janggal atau membingungkan saat dioperasikan.",
                    9 => "Saya merasa sangat percaya diri saat mengoperasikan sistem dashboard autentikasi ini.",
                    10 => "Saya harus belajar banyak hal baru sebelum saya dapat mengoperasikan sistem ini dengan lancar."
                ];
                $defaults = [1=>5, 2=>1, 3=>5, 4=>1, 5=>4, 6=>1, 7=>5, 8=>1, 9=>5, 10=>1];
            @endphp

            @foreach($questions as $num => $qText)
                <div class="p-3.5 rounded-xl border border-slate-200/80 bg-slate-50/40 space-y-2.5 hover:bg-slate-50 transition-colors">
                    <div class="flex items-start gap-2.5">
                        <span class="w-5 h-5 rounded-md bg-indigo-100 text-indigo-800 font-bold text-xs flex items-center justify-center shrink-0 font-mono">
                            {{ $num }}
                        </span>
                        <p class="text-xs font-semibold text-slate-800 leading-snug">
                            {{ $qText }}
                        </p>
                    </div>

                    <!-- 5-Point Likert Scale Buttons -->
                    <div class="grid grid-cols-5 gap-1.5 sm:gap-2 text-center pt-1">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="tab_q{{ $num }}" value="{{ $i }}" {{ ($defaults[$num] ?? 3) == $i ? 'checked' : '' }} onchange="calculateTabLiveSus()" class="peer sr-only">
                                <div class="py-1.5 px-2 rounded-lg border border-slate-200 bg-white text-slate-700 font-mono text-xs font-bold transition-all peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 hover:border-slate-300 shadow-2xs">
                                    <span>{{ $i }}</span>
                                    <span class="text-[9px] font-sans font-normal block sm:hidden">
                                        @if($i===1) STS @elseif($i===5) SS @endif
                                    </span>
                                    <span class="text-[9px] font-sans font-normal hidden sm:block">
                                        @if($i===1) Sangat Tdk Setuju @elseif($i===2) Tdk Setuju @elseif($i===3) Netral @elseif($i===4) Setuju @elseif($i===5) Sangat Setuju @endif
                                    </span>
                                </div>
                            </label>
                        @endfor
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Qualitative Feedback -->
        <div>
            <label for="tab_feedback" class="block text-xs font-bold text-slate-700 uppercase font-mono mb-1">SARAN / MASUKAN KUALITATIF ERGONOMI DASHBOARD</label>
            <textarea id="tab_feedback" name="feedback" rows="2" placeholder="Catatan ergonomi operator..." class="w-full px-3 py-2 text-xs rounded-xl border border-slate-300 bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium"></textarea>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end pt-2">
            <button type="submit" id="btn-submit-tab-sus" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all flex items-center gap-2 shadow-xs">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Simpan Kuesioner SUS</span>
            </button>
        </div>

    </form>

</div>
