<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kuesioner System Usability Scale (SUS) — Poltekad</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700;800&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Poppins"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full font-sans text-slate-800 bg-slate-50 antialiased selection:bg-indigo-100 selection:text-indigo-800">

    <!-- Topbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-40 shadow-xs">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center hover:bg-slate-200 transition-colors">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <h1 class="font-extrabold text-sm sm:text-base text-slate-900 tracking-tight">EVALUASI USABILITY (SUS)</h1>
                    <p class="text-[11px] text-slate-500 font-medium">Kuesioner Standar System Usability Scale (John Brooke, 1986)</p>
                </div>
            </div>
            
            <a href="{{ route('usability.results') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                <i class="fa-solid fa-chart-simple text-slate-500"></i>
                <span>Lihat Hasil Rekap</span>
            </a>
        </div>
    </header>

    <!-- Main Questionnaire Container -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 space-y-6">

        <!-- Research Info Banner -->
        <section class="bg-white rounded-xl border border-slate-200 p-5 shadow-2xs">
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                    <i class="fa-solid fa-clipboard-question text-lg"></i>
                </div>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-extrabold text-indigo-950 uppercase font-mono">PENELITIAN POLTEKAD KODIKLATAD</span>
                        <span class="px-2 py-0.2 rounded-full text-[9px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200 font-mono">TARGET SKOR &gt; 75</span>
                    </div>
                    <h2 class="text-base font-extrabold text-slate-900">Perancangan Dashboard Autentikasi Real-Time Berbasis HCD &amp; WebSocket</h2>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Silakan berikan penilaian objektif berdasarkan pengalaman Anda mengoperasikan antarmuka dashboard. Skala penilaian menggunakan skala Likert 1 (Sangat Tidak Setuju) hingga 5 (Sangat Setuju).
                    </p>
                </div>
            </div>
        </section>

        <!-- Live Score Calculator Card -->
        <section class="bg-slate-900 text-white rounded-2xl p-5 shadow-md border border-slate-800 sticky top-20 z-30 flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase font-mono block">ESTIMASI SKOR SUS ANDA SAAT INI:</span>
                <div class="flex items-baseline gap-2 mt-0.5">
                    <span id="live-sus-score" class="text-3xl font-extrabold font-mono text-emerald-400">80.0</span>
                    <span class="text-xs text-slate-400 font-mono">/ 100</span>
                    <span id="live-sus-grade" class="ml-2 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-900/80 text-emerald-300 border border-emerald-600 font-mono">GRADE A (EXCELLENT)</span>
                </div>
            </div>
            
            <div class="flex items-center gap-2 text-xs font-mono">
                <span class="text-slate-400">Indikator Ketercapaian:</span>
                <span id="live-sus-target-indicator" class="px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/30">
                    <i class="fa-solid fa-circle-check"></i> TARGET &gt;75 TERPENUHI
                </span>
            </div>
        </section>

        <!-- Form Formatted -->
        <form id="sus-form" onsubmit="handleSusSubmit(event)" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
            @csrf

            <!-- Respondent Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pb-4 border-b border-slate-100">
                <div>
                    <label for="respondent_name" class="block text-xs font-bold text-slate-700 uppercase font-mono mb-1.5">NAMA OPERATOR / RESPONDEN <span class="text-rose-500">*</span></label>
                    <input type="text" id="respondent_name" name="respondent_name" value="{{ auth()->user()->name ?? 'Letnan Dua Antok' }}" required class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                </div>
                <div>
                    <label for="respondent_role" class="block text-xs font-bold text-slate-700 uppercase font-mono mb-1.5">PERAN / JABATAN OPERASIONAL</label>
                    <select id="respondent_role" name="respondent_role" class="w-full px-3.5 py-2 text-xs rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800">
                        <option value="Perwira Jaga Komando">Perwira Jaga Komando</option>
                        <option value="Operator Pos Gerbang Utama">Operator Pos Gerbang Utama</option>
                        <option value="Operator Patroli Lapangan">Operator Patroli Lapangan</option>
                        <option value="Perwira Siber & Komunikasi">Perwira Siber & Komunikasi</option>
                        <option value="Operator Barak Taruna">Operator Barak Taruna</option>
                    </select>
                </div>
            </div>

            <!-- 10 Questions List -->
            <div class="space-y-6">
                
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
                    <div class="p-4 rounded-xl border border-slate-200/80 bg-slate-50/40 space-y-3 transition-colors hover:bg-slate-50" id="card-q{{ $num }}">
                        <div class="flex items-start gap-3">
                            <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-800 font-extrabold text-xs flex items-center justify-center shrink-0 font-mono">
                                {{ $num }}
                            </span>
                            <p class="text-xs sm:text-sm font-semibold text-slate-900 leading-snug">
                                {{ $qText }}
                            </p>
                        </div>

                        <!-- 5-Point Likert Scale -->
                        <div class="pt-2">
                            <div class="grid grid-cols-5 gap-2 sm:gap-3 text-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="q{{ $num }}" value="{{ $i }}" {{ ($defaults[$num] ?? 3) == $i ? 'checked' : '' }} onchange="calculateLiveSusScore()" class="peer sr-only">
                                        <div class="p-2 sm:p-2.5 rounded-xl border border-slate-200 bg-white text-slate-700 font-mono text-xs font-bold transition-all peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 peer-checked:shadow-xs hover:border-slate-300">
                                            <div>{{ $i }}</div>
                                            <div class="text-[9px] font-sans font-normal mt-0.5 hidden sm:block">
                                                @if($i === 1) Sangat Tdk Setuju
                                                @elseif($i === 2) Tdk Setuju
                                                @elseif($i === 3) Netral
                                                @elseif($i === 4) Setuju
                                                @elseif($i === 5) Sangat Setuju
                                                @endif
                                            </div>
                                        </div>
                                    </label>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Feedback Qualitative Textarea -->
            <div class="pt-2">
                <label for="feedback" class="block text-xs font-bold text-slate-700 uppercase font-mono mb-1.5">SARAN / MASUKAN KUALITATIF UNTUK ERGONOMI SISTEM (OPSIONAL)</label>
                <textarea id="feedback" name="feedback" rows="3" placeholder="Contoh: Tata letak thumb zone di tablet sangat membantu saat mobilitas patroli..." class="w-full px-3.5 py-2.5 text-xs rounded-xl border border-slate-300 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium text-slate-800"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
                <a href="{{ route('dashboard') }}" class="px-4 py-2.5 rounded-xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition-colors">
                    Kembali ke Dashboard
                </a>
                <button type="submit" id="btn-submit-sus" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-700 active:scale-95 transition-all flex items-center gap-2 shadow-xs">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Kirim Hasil Kuesioner SUS</span>
                </button>
            </div>

        </form>

    </main>

    <!-- JS Form Calculation -->
    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function calculateLiveSusScore() {
            let oddSum = 0;
            let evenSum = 0;

            for (let i = 1; i <= 10; i++) {
                const checked = document.querySelector(`input[name="q${i}"]:checked`);
                const val = checked ? parseInt(checked.value) : 3;
                if (i % 2 === 1) {
                    oddSum += (val - 1);
                } else {
                    evenSum += (5 - val);
                }
            }

            const totalScore = (oddSum + evenSum) * 2.5;

            const scoreEl = document.getElementById('live-sus-score');
            const gradeEl = document.getElementById('live-sus-grade');
            const targetEl = document.getElementById('live-sus-target-indicator');

            if (scoreEl) scoreEl.innerText = totalScore.toFixed(1);

            let gradeText = 'GRADE F (POOR)';
            let gradeClass = 'ml-2 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-rose-900/80 text-rose-300 border border-rose-600 font-mono';

            if (totalScore >= 85.0) {
                gradeText = 'GRADE A+ (BEST IMAGINABLE)';
                gradeClass = 'ml-2 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-900/80 text-emerald-300 border border-emerald-600 font-mono';
            } else if (totalScore >= 80.3) {
                gradeText = 'GRADE A (EXCELLENT)';
                gradeClass = 'ml-2 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-900/80 text-emerald-300 border border-emerald-600 font-mono';
            } else if (totalScore >= 74.0) {
                gradeText = 'GRADE B (GOOD / ACCEPTABLE)';
                gradeClass = 'ml-2 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-teal-900/80 text-teal-300 border border-teal-600 font-mono';
            } else if (totalScore >= 68.0) {
                gradeText = 'GRADE C (OK)';
                gradeClass = 'ml-2 px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-amber-900/80 text-amber-300 border border-amber-600 font-mono';
            }

            if (gradeEl) {
                gradeEl.innerText = gradeText;
                gradeEl.className = gradeClass;
            }

            if (targetEl) {
                if (totalScore >= 75.0) {
                    targetEl.innerHTML = `<i class="fa-solid fa-circle-check"></i> TARGET &gt;75 TERPENUHI`;
                    targetEl.className = "px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 font-bold border border-emerald-500/30";
                } else {
                    targetEl.innerHTML = `<i class="fa-solid fa-circle-xmark"></i> DI BAWAH TARGET &gt;75`;
                    targetEl.className = "px-2.5 py-1 rounded-lg bg-rose-500/20 text-rose-300 font-bold border border-rose-500/30";
                }
            }
        }

        async function handleSusSubmit(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-submit-sus');
            btn.disabled = true;
            btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...`;

            const formData = new FormData(document.getElementById('sus-form'));
            const payload = {
                respondent_name: formData.get('respondent_name'),
                respondent_role: formData.get('respondent_role'),
                q1: parseInt(formData.get('q1')),
                q2: parseInt(formData.get('q2')),
                q3: parseInt(formData.get('q3')),
                q4: parseInt(formData.get('q4')),
                q5: parseInt(formData.get('q5')),
                q6: parseInt(formData.get('q6')),
                q7: parseInt(formData.get('q7')),
                q8: parseInt(formData.get('q8')),
                q9: parseInt(formData.get('q9')),
                q10: parseInt(formData.get('q10')),
                feedback: formData.get('feedback'),
            };

            try {
                const res = await fetch('/api/usability/sus/submit', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (data.success) {
                    alert(`✅ Kuesioner Berhasil Disimpan!\nSkor SUS: ${data.score} (${data.adjective})\nStatus Target: ${data.meets_target ? 'TERPENUHI (>75)' : 'BELUM'}`);
                    window.location.href = "{{ route('usability.results') }}";
                } else {
                    alert('Gagal menyimpan kuesioner.');
                    btn.disabled = false;
                }
            } catch (err) {
                alert('Terjadi kesalahan saat mengirim data.');
                btn.disabled = false;
            }
        }

        // Run once on load
        window.addEventListener('DOMContentLoaded', calculateLiveSusScore);
    </script>

</body>
</html>
